<?php
class User_model extends AZ_Model
{
    public $_table = 'user';

    public function __construct()
    {
        parent::__construct();
    }
    public function login($mobile,$password){
        $where['mobile'] = $mobile;
        $where['password'] = $password;
        $result = $this->db->select('id,mobile,name,sex,status')->where($where)->get($this->_table)->row();
        if(empty($result)) {
            output_response(1,[],'用户名或密码错误!');
        }
        //判断是否可以登录
        if($result->status == 1 ) {
            output_response(1,[],'该用户被冻结!');
        }
        unset($result->status);
        // 更新登录信息
        $this->db->set('updateTime',date('Y-m-d H:i:s'));
        $this->db->where('id',  $result->id);
        $this->db->update('user');

        return $this->userInfo($result->id);
    }
    public function register($name,$sex,$mobile,$password,$channel=''){
        $object['name']=$name;
        $object['sex']=$sex;
        $object['mobile']=$mobile;
        $object['password']=$password;
        $object['channel']=$channel;

        $re=$this->db->insert($this->_table, $object);
        if($re){
            return $this->userInfo($this->db->insert_id());
        }
        return false;
    }
    function quickLogin($mobile,$channel=9,$iscode=false,$code='',$password=''){
        $re=$this->db->from('user')->select('id')->where('mobile',$mobile)->get()->row();
        if($re){
            return $this->userInfo($re->id);
        }else{
           $password1=md5($mobile);
           if($password!=''){
               $password1=md5($password);
           }
            $num=rand(000000,999999);
            $object['name']='yh'.$num;
            $object['sex']=2;
            $object['mobile']=$mobile;
            $object['password']=$password1;
            $object['channel']=$channel;
            $re=$this->db->insert($this->_table, $object);

            if($re){
                $id=$this->db->insert_id();
                if($iscode){
                    $reid=0;
                    $re=$this->db->where('code',$code)->select('id')->get('merchant')->row();
                    if($re){
                       $reid=$re->id;
                    }
                    $arr['mid']=$reid;
                    $arr['mobile']=$mobile;
                    $arr['userId']=$id;
                    $this->db->insert('channelregister',$arr);
                    return true;
                }
                return $this->userInfo($id);
            }else{
                return false;
            }
        }
    }
    function userInfo($id){
        $result = $this->db->select('id,name,mobile,sex,updateTime')->where(['id'=>$id])->get($this->_table)->row();
        if($result && $result->updateTime>1){
            $update['updateTime']='0000-00-00 00:00:00';
            $this->db->where(['id'=>$id])->update($this->_table, $update);
        }

        $data=$this->db->from('config')->get()->row();
        if($data->certification==1 && isset($id)){
            $re=$this->user->certification($id);
            if($re){
               $data->certification=0;
            }
        }
        $result->certification=$data->certification;
        return $result;
    }
    function certification($id){
        $result = $this->db->select('id,realName,realCard')->where(['id'=>$id])->get($this->_table)->row();
        if(!$result || $result->realName=='' || $result->realCard==''){
            return false;
        }
        return true;
    }
    function real($id,$name,$card){
        $date=$this->db->select('id')->where(['realCard'=>$card])->get($this->_table)->row();
        if($date){
            return false;
        }
        $age=$this->get_birthday($card);
        $re=$this->updates(array('id'=>$id),array('realName'=>$name,'realCard'=>$card,'age'=>$age));
        return true;
    }
    function get_birthday($idcard) {
        if(empty($idcard)) return null; 
        $bir = substr($idcard, 6, 8);
        $year = (int) substr($bir, 0, 4);
        return $year;
    }
 
    public function mobileProving($mobile){
        $result = $this->db->where('mobile',$mobile)->get($this->_table)->row();
        if($result){
            return false;
        }
        return true;
    }
    function changeForget($mobile,$password){
        $data=$this->db->from($this->_table)->where('mobile',$mobile)->get()->row();
        if(!$data){
            output_response(1,[],'账号错误!');
        }
        $this->db->set('password',$password);
        $this->db->where('id',  $data->id);
        $re=$this->db->update('user');
        if($re){
            return true;
        }
        output_response(1,[],'修改失败!');;
    }
    function valiInsert($data){
        return $this->db->insert('validate',$data);
    }
    function valiyanzheng($where){
        $verifyArr=$this->db->from('validate')->where($where)->get()->row();
         if(!$verifyArr ){
             output_response(1,[],'验证码错误！');
         }
        $this->db->where('id',$verifyArr->id)->update('validate',['status'=>1]);
        return true;
    }
}