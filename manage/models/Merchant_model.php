<?php
class Merchant_model extends AZ_Model
{
    public $_table = 'merchant';

    public function __construct()
    {
        parent::__construct();
    }
    function merchantAdd($post){
        if($post['id']==''){
            $r=$this->queryOne(['username'=>$post['username']]);
            $admin=$this->db->from('admin')->where(['username'=>$post['username']])->get()->row();
            if($r || $admin){
                return '账户不能重复';
            }
            unset($post['id']);
            if($post['name']=='' || $post['username']=='' || $post['password']==''){
                return '都不能为空';
            }
            $post['password']=md5($post['password']);
            $post['code']=md5($post['username']);
            return  $this->db->insert('merchant',$post);
        }else{
            if($post['id']=='' || $post['name']==''){
                return '都不能为空';
            }
            if($post['password']!=''){
                $data['password']=md5($post['password']);
            }
            $data['name']=$post['name'];
            return  $this->db->where('id',$post['id'])->update('merchant',$data);
        }
    }
    //登陆
    function getAdminInfo($username,$pwd) {
        $admin=$this->db->select('username')->from($this->_table)->where('username', $username)->get()->row();
        if(!$admin || $admin->username!=$username){
            return false;
        }
        return $this->db->select()->from($this->_table)->where('username', $username)->where('password', $pwd)->where('status', 0)->get()->row();
    }
    function channelregister($where, $like,$field,$limit, $start){
        $this->db->group_by('c.mobile');
        if(is_array($like)&& !empty($like)) {
             $this->db->like($like);
        }
        if(!empty($where)){
            $this->db->where($where);
        }
        $data = $this->db->select($filed)->from('channelregister c')->join('user u','u.id=c.userId')
        ->where(['u.updateTime<'=>'1'])
        ->order_by('c.id','desc')
        ->limit($limit,$start)->get()->result();

        return $data;
    }
    function channelregister_join($where,$filed, $like)
    {
        $this->db->group_by('channelregister.mobile');
        if(is_array($like)&& !empty($like)) {
             $this->db->like($like);
        }
        if(!empty($where)){
            $this->db->where($where);
        }
        $data = $this->db->select($filed)->from('channelregister')->join('user','channelregister.userId=user.id')->get()->result();

       // 遍历处理一下
        foreach($data as $v)
        {   
            $v->age= date('Y') - $v->age;
            
        }
        return $data;
        
    }
    function channelregisterCount($where, $like){
        if(is_array($like)&& !empty($like)) {
             $this->db->like($like);
        }
        if(!empty($where)){
            $this->db->where($where);
        }
        $this->db->group_by('c.mobile');
        $data= $this->db->select('c.id')->from('channelregister c')->join('user u','u.id=c.userId')->where(['u.updateTime<'=>'1'])->get()->result();
        return count($data);
    }
    function merchantDel($id){
        return $this->db->where('id',$id)->delete('merchant');
    }
  function tongji($page,$pageSize,$qudaoId=''){
        $data=$this->db->from('shop')->select('createdTime')->get()->result();
        $count2=0;
        foreach ($data as $v) {
            $time=substr($v->createdTime,0,10)==date('Y-m-d')?substr(date('Y-m-d',strtotime($v->createdTime)-60*60*24),0,10):substr($v->createdTime,0,10);
          
            $count2+=(strtotime(date('Y-m-d'))-strtotime($time))/(60*60*24);
        }
        unset($data);
        $data=$this->db->from('shop')->select('createdTime')->order_by('createdTime','asc')->get()->row();
        $time=substr($data->createdTime,0,10)==date('Y-m-d')?substr(date('Y-m-d',strtotime($data->createdTime)-60*60*24),0,10):substr($data->createdTime,0,10);
        $count=(strtotime(date('Y-m-d'))-strtotime($time))/(60*60*24);

        $y=0;
        $n=$page*$pageSize-$pageSize+1;
        for($i=0;$i<$count;$i++){
            $time= date('Y-m-d', strtotime(date('Y-m-d H:i:s'))-$i*60*60*24);

            $shop=$this->db->from('shop')->select('id,shopName')->where('createdTime<',$time.' 23:59:59')->get()->result();

            foreach ($shop as $v) {
                if($n!=0){
                    $n--;
                }
                if($y<$pageSize && $n==0 &&  $i>=$n){
                    $y++;
                    unset($arr);
                    $arr=new stdClass();
                    $arr->time=$time;
                    $arr->name=$v->shopName;
                    $where['createdTime>']=$time;
                    $where['createdTime<']= date('Y-m-d', strtotime($time)+60*60*24);
                   

                    if($qudaoId>0){
                        $where2['s.createdTime>']=$time;
                        $where2['s.createdTime<']= date('Y-m-d', strtotime($time)+60*60*24);
                        $count4=$this->db->from('shopApply s')
                        ->join('channelregister c','c.userId=s.userId')
                        ->select('s.id')
                        ->where('s.sid',$v->id)
                        ->where('s.type',0)
                        ->where('c.mid',$qudaoId)
                        ->where($where2)
                        ->order_by('s.createdTime','asc')
                        ->group_by('s.userId')
                        ->get()->result();

                        $count5=$this->db->from('shopApply s')
                        ->join('channelregister c','c.userId=s.userId')
                        ->select('s.id')
                        ->where('s.sid',$v->id)
                        ->where('s.type',1)
                        ->where('c.mid',$qudaoId)
                        ->where($where2)
                        ->order_by('s.createdTime','asc')
                        ->group_by('s.userId')
                        ->get()->result();
                    }else{
                        $count4=$this->db->from('shopApply')->select('id')->where('sid',$v->id)->where('type',0)->where($where)->order_by('createdTime','asc')->group_by('userId')->get()->result();
                        $count5=$this->db->from('shopApply')->select('id')->where('sid',$v->id)->where('type',1)->where($where)->order_by('createdTime','asc')->group_by('userId')->get()->result(); 
                    }



                    $arr->uv=count($count4);
                    $arr->num=count($count5);
                    $arr->bi=round(($arr->num/$arr->uv)*100,2).'%';
                    $data1[]=$arr;
                }
               
            }

            
        }
        unset($data);
        $data['data']=$data1;
        $data['count']=$count2;
        return $data;
    }
}