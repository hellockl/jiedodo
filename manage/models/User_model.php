<?php
class User_model extends AZ_Model
{
    public $_table = 'user';

    public function __construct()
    {
        parent::__construct();
    }
    function home($where=''){
        if($where){
            $this->db->where($where);
        }
        $register=$this->db->from('user')->select('channel, count(*) as count')->group_by('channel')->get()->result();
//        $register=$this->db->from('register')->select('channel, count(*) as count')->group_by('channel')->get()->result();
        $reg1=[];
        foreach($register as $v){
            $reg1[$v->channel]=$v;
        }
        $arr='';
        $brr='';
        $crr='';
        foreach($register as $v){
            $title=$v->channel;
            $channel=$this->db->from('channel')->select('title')->where('id',$v->channel)->get()->row();
            if($channel){
                $title=$channel->title;
            }
            $arr.="'$title',";
            $brr.="'$v->count',";
            
            $num1=$this->db->from('user u')->join('register r','u.id=r.userId')->where('u.channel',$v->channel)->count_all_results();
            $crr.="'".$num1."',";
        }
        if($arr==''){
            $channel=$this->db->from('channel')->select('title')->get()->result();
            foreach($channel as $v){
                $arr.="'$v->title',";
                $brr.="0,";
                $crr.="0,";
            }
        }
        $arr=rtrim($arr,',');
        $brr=rtrim($brr,',');
        $crr=rtrim($crr,',');
        $data=new stdClass();
        $data->a="[".$arr."]";
        $data->b="[".$brr."]";
        $data->c="[".$crr."]";
        $data->count1=0;
        $data->count2=0;
        $data->count3=0;
        $data->count4=0;
        $data->count5=0;
        $data->count6=0;
        $data->count7=0;
        $data->zhuce=$this->db->from('user')->count_all_results();

        $data->count1=$this->db->from('user')->where('createdTime>',date('Y-m-d'))->count_all_results();
        $data->count2=$this->db->from('user')->where('createdTime>',date('Y-m'))->count_all_results();

        $rs=$this->db->from('register')->where('createdTime>',date('Y-m-d'))->group_by('userId')->get()->result();//->count_all_results();
        $data->count3=count($rs);
        $count4= $this->db->from('register')->select('id')->where('createdTime>',date('Y-m-d'))->group_by('userId')->get()->result();
        $data->count4=count($count4);

        $count5=$this->db->from('register')->where('createdTime>',date('Y-m'))->group_by('userId')->get()->result();//->count_all_results();
        $data->count5=count($count5);
        $count6= $this->db->from('register')->select('id')->where('createdTime>',date('Y-m'))->group_by('userId')->get()->result();
        $data->count6=count($count6);
        $data->count7=$data->count3/$data->count1;
        $data->count7=round($data->count7);
        return $data;
    }
    function userAll($where, $like, $limit, $start,$lai=''){
        if($lai>0){
            if($where) {
                $where1['u.createdTime >']= $where['createdTime >'];
                $where1['u.createdTime <']= $where['createdTime <'];
                $this->db->where($where1);
            }
            if($like!=''){
                $like1['u.mobile']= $like['mobile'];
                $this->db->like($like1);
            }
            $data = $this->db->from('user u')->select('u.*')
            ->join('channelregister c','c.userId=u.id')
            ->where('c.mid',$lai)
            ->limit($limit, $start)
            ->order_by('u.id desc')
            ->get()->result();

        }else{
            if($where) {
                $this->db->where($where);
            }
            if($like!=''){
                $this->db->like($like);
            }
            $data = $this->db->from($this->_table)->limit($limit, $start)->order_by('id desc')->get()->result();
        }
        
        foreach ($data as $v) {
            $fen=$this->db->from('register')->where('userId',$v->id)->where('createdTime >',date('Y-m-d'))->group_by('shopId')->get()->result();
            $v->fen=count($fen);
            $channel=$this->db->from('channelregister c')->select('m.name')->join('merchant m','m.id=c.mid')->where('c.userId',$v->id)->get()->row();

            $v->lai='';
            if($channel){
                 $v->lai=$channel->name;
            }
        }
        return $data;
    }
    function userCount($where, $like,$lai=''){

        if($lai>0){
            if($where) {
                $where1['u.createdTime >']= $where['createdTime >'];
                $where1['u.createdTime <']= $where['createdTime <'];
                $this->db->where($where1);
            }
            if($like!=''){
                $like1['u.mobile']= $like['mobile'];
                $this->db->like($like1);
            }
            $data = $this->db->from('user u')->select('u.*')
            ->join('channelregister c','c.userId=u.id')
            ->where('c.mid',$lai)
            ->count_all_results();
           
        }else{
            if($where) {
                $this->db->where($where);
            }
            if($like!=''){
                $this->db->like($like);
            }
            $data = $this->db->from($this->_table)->count_all_results();
        }
        

       return $data;
    }
    function testingList($id){
        $data=$this->db->from('testing')->select('id,payAmount,transactionId,payTime')->where('userId',$id)->where('status',1)->order_by('id desc')->get()->result();
        foreach($data as $v){
            $v->list=$this->db->select('s.shopName,s.logo,s.abstract,s.minQuota,s.maxQuota,s.interest')
                ->from('testing_shop t')
                ->join('shop s','s.id=t.shopId')
                ->where('t.testingId',$v->id)
                ->get()->result();
        }
        return $data;
    }
    function registerList($id){
         $data=$this->db->select('r.createdTime,s.shopName,s.logo,s.abstract,s.minQuota,s.maxQuota,s.interest')
             ->from('register r')
             ->join('shop s','s.id=r.shopId')
             ->where('r.userId',$id)
             ->order_by('r.id desc')->get()->result();
        return $data;
    }
    function userFen($id,$starttime='',$endtime=''){
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
            $count=$this->db->from('register')->where('userId',$id)->where($where)->group_by('shopId')->get()->result();//->count_all_results();
            $count=count($count);
            $arr.="'".date('m-d',$time+24*60*60*$i)."',";
            $brr.="'$count',";
        }
        $arr=rtrim($arr,',');
        $brr=rtrim($brr,',');
        $data=new stdClass();
        $data->a="[".$arr."]";
        $data->b="[".$brr."]";
        return $data;
    }
}