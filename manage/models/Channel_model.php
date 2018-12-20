<?php
class Channel_model extends AZ_Model
{

    public function __construct()
    {
        parent::__construct();

    }
    function dayUser($starttime='',$endtime='',$type=1){
        $arr='';
        $brr='';
        $num=10;
        $y=1;
        if($type==1){
            $table='user';
        }elseif($type==2){
            $table='register';
        }elseif($type==3){
            $table='register';
        }

        $time=strtotime(date('y-m-d'))-10*24*60*60;
        if($starttime && $endtime){
            $num=( strtotime($endtime)-strtotime($starttime))/(24*60*60);
            $time=strtotime($starttime);
            $y=0;
        }

        for($i=$y;$i<=$num;$i++){

            $stime= date('Y-m-d',$time+24*60*60*$i);
            $etime= date('Y-m-d',$time+24*60*60*($i+1));
            $where=[];
            if($stime && $etime){
                $where['createdTime >']=$stime;
                $where['createdTime <']=$etime;
            }
            if($type==3){
                $count=$this->db->from($table)->select('id')->where($where)->group_by('userId')->get()->result();
                $count=count($count);
            }elseif($type==2){
                $count=$this->db->from($table)->where($where)->group_by('userId')->get()->result();//->count_all_results();
                $count=count($count);
            }else{
                $count=$this->db->from($table)->where($where)->count_all_results();
            }


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
    function dayFen($starttime='',$endtime=''){
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
            $where=[];
            if($stime && $etime){
                $where['createdTime >']=$stime;
                $where['createdTime <']=$etime;
            }
            $register=$this->db->from('register')->where($where)->group_by('userId')->get()->result();//->count_all_results();
            $register=count($register);
            $user=$this->db->from('user')->where($where)->count_all_results();
            $count=round($register /$user);
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
    function monthUser($nian='',$type=1){
        if($nian==''){
            $nian=date('Y');
        }
        if($type==1){
            $table='user';
        }elseif($type==2){
            $table='register';
        }elseif($type==3){
            $table='register';
        }
        $arr='';
        $brr='';
        for($i=1;$i<=12;$i++){
            $stime= date('Y-m',strtotime($nian.'-'.$i.'-01'));
            $etime= date('Y-m',strtotime($nian.'-'.($i+1).'-01'));
            $where=[];
            if($stime && $etime){
                $where['createdTime >']=$stime;
                $where['createdTime <']=$etime;
            }
            if($type==3){
                $count=$this->db->from($table)->select('id')->where($where)->group_by('userId')->get()->result();
                $count=count($count);
            }else{
                $count=$this->db->from($table)->where($where)->count_all_results();
            }


            $arr.="'".date('Y-m',strtotime($nian.'-'.$i.'-01'))."',";
            $brr.="'$count',";
        }
        $arr=rtrim($arr,',');
        $brr=rtrim($brr,',');
        $data=new stdClass();
        $data->a="[".$arr."]";
        $data->b="[".$brr."]";
        return $data;
    }
    function shi(){
        $shi=$this->db->from('user')->select('createdTime')->order_by('createdTime','asc')->get()->row();
        $nian=date('Y',strtotime($shi->createdTime));
        $time=date('Y')-$nian;
        $arr[]=(int)date('Y');
        for($i=1;$i<=$time;$i++){
            $arr[]=date('Y')-$i;
        }
       return $arr;

    }
}