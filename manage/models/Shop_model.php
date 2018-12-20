<?php
class Shop_model extends AZ_Model
{
    public $_table = 'shop';

    public function __construct()
    {
        parent::__construct();
    }
    function shopAll($where, $like, $limit, $start){
        if($where) {
            $this->db->where($where);
        }
        if($like!=''){
            $this->db->like($like);
        }
        $data=$this->db->from($this->_table)->limit($limit, $start)->order_by('hot desc')->order_by('sort asc')->order_by('id desc')->get()->result();
        foreach ($data as $key => $value) {
            $fen=$this->db->from('register')->select('id')->where('shopId',$value->id)->where('createdTime>',date('Y-m-d'))->group_by('userId')->get()->result();
            $value->count=count($fen);
        }
        return $data;
    }
    function shopCount($where, $like){
        if($where) {
            $this->db->where($where);
        }
        if($like!=''){
            $this->db->like($like);
        }
        return $this->db->from($this->_table)->count_all_results();
    }
    function shopAdd($post){
        if($post['id']==''){
            unset($post['id']);
            if($post['logo']=='' || $post['num']=='' || $post['shopName']=='' || $post['abstract']=='' || $post['minQuota']=='' || $post['maxQuota']=='' || $post['cycle']=='' || $post['interest']=='' || $post['condition']=='' || $post['procedure']=='' || $post['remarks']==''){
                return '都不能为空';
            }
            if(!isset($post['hot'])){
                $post['hot']=0;
            }
            return  $this->db->insert('shop',$post);
        }else{
            if($post['id']=='' || $post['shopName']=='' || $post['num']=='' || $post['abstract']=='' || $post['minQuota']=='' || $post['maxQuota']=='' || $post['cycle']=='' || $post['interest']=='' || $post['condition']=='' || $post['procedure']=='' || $post['remarks']==''){
                return '都不能为空';
            }
            if($post['registerType']!=1 && $post['registerUrl']==''){
                return '注册链接不能为空';
            }
            if($post['logo']==''){
                unset($post['logo']);
            }
            if(!isset($post['hot'])){
                $post['hot']=0;
            }
            return  $this->db->where('id',$post['id'])->update('shop',$post);
        }
    }
    function shopMoney($id,$type,$money){
        $arr['shopId']=$id;
        $arr['type']=$type;
        $arr['amount']=$money;
        if($type==1){
            $arr['desc']='管理员扣款';
        }else{
            $arr['desc']='管理员充值';
        }
        return $this->db->insert('money',$arr);
    }
    function jiluMoney($id){
        return $this->db->from('money')->where('shopId',$id)->get()->result();
    }
    function shenqing($id){
        $data=$this->db->select('u.nickname,u.headImgUrl,r.mobile,r.createdTime')
            ->from('register r')
            ->join('user u','u.id=r.userId')
            ->where('r.shopId',$id)
            ->order_by('r.id desc')
            ->get()
            ->result();
        return $data;
    }
    function registetype(){
        $depart=$this->db->select('id,title')->from('type')->where('pid',0)->get()->result();
        foreach($depart as $k=>$v){
            $v->office=$this->db->select('id,title,pid')->from('type')->where('pid',$v->id)->where('pid!=',0)->get()->result();
            $depart1[$v->id]=$v;
        }
        return $depart1?$depart1:[];
    }
    function tongji($id,$starttime='',$endtime=''){
        $arr='';
        $brr='';
        $num=10;
        $y=1;
        $time=strtotime(date('y-m-d'))-10*24*60*60;
        if($starttime && $endtime){
            $num=( strtotime($endtime)-strtotime($starttime))/(24*60*60);
            $time=strtotime($starttime);
            $y=0;
        }

        for($i=$y;$i<=$num;$i++){
            $stime= date('Y-m-d',$time+24*60*60*$i);
            $etime= date('Y-m-d',$time+24*60*60*($i+1));
            if($stime && $etime){
                $where['createdTime >']=$stime;
                $where['createdTime <']=$etime;
            }
            $count=$this->db->from('register')->where('shopId',$id)->where($where)->group_by('userId')->get()->result();//->count_all_results();
            $count=count($count);
            $arr.="'".date('m-d',$time+24*60*60*$i)."',";
            $brr.="'$count',";
        }
        $arr=rtrim($arr,',');
        $brr=rtrim($brr,',');
        $data=new stdClass();
        $data->a="[".$arr."]";
        $data->b="[".$brr."]";
        $count=$this->db->from('register')->where('shopId',$id)->get()->result();
        $data->c=count($count);
        return $data;
    }
    function advance($id=NUll){
        if(!$id){
            return false;
        }
        return $this->db->from('advance')->where('shopId',$id)->get()->result();
    }
    function advanceCount($id=''){
        $data=$this->db->from('advance')->select('sum(money) as count')->where('shopId',$id)->where('type',1)->get()->row();
        if($data && $data->count){
            return $data->count;
        }
        return 0;
    }
    function advanceAdd($id,$money){
        return $this->db->insert('advance',array('shopId'=>$id,'money'=>$money));
    }
    function advanceDel($id){
        return $this->db->where('id',$id)->delete('advance');
    }
}