<?php
class Home_model extends AZ_Model{
    public function __construct()
    {
        parent::__construct();
    }
    function banner(){
        $data=$this->db->from('banner')->select('img,url')->where('type',0)->where('status',0)->order_by('id','desc')->get()->result();
        foreach($data as $v){
            $v->img= $this->cdn.$v->img;
        }
        return $data;
    }
    function adv($type=1){
        $data=$this->db->from('banner')->select('img,url')->where('type',$type)->where('status',0)->order_by('id','desc')->get()->row();
        if(!$data){
            return new stdClass();
        }
        $data->img= $this->cdn.$data->img;
        return $data;
    }
    function shop(){
        $data= $this->db->from('shop')->select('id,shopName,logo,minQuota,maxQuota,interest,abstract,hot')->order_by('hot','desc')->order_by('sort','asc')->order_by('id','desc')->where('status',0)->limit(5)->get()->result();
        foreach($data as $v){
            $v->logo= $this->cdn.$v->logo;
        }
        return $data;
    }
    function shopList($searchId='',$priceId='',$sortId='',$page){
        if($searchId!=0){
            $this->db->where('searchId',$searchId);
        }
        if($priceId!=0){
            $this->db->where('priceId',$priceId);
        }
//        array(
//            array('id'=>1,'title'=> '综合排序'),
//            array('id'=>2,'title'=> '成功率高'),
//            array('id'=>3,'title'=> '利率最低'),
//            array('id'=>4,'title'=> '额度最高'),
//            array('id'=>5,'title'=> '放款最快'),
//        );
        if($sortId==1){

        }elseif($sortId==2){
            $this->db->order_by('success','desc');
        }elseif($sortId==3){
            $this->db->order_by('interest','asc');
        }elseif($sortId==4){
            $this->db->order_by('maxQuota','desc');
        }elseif($sortId==5){
            $this->db->order_by('speed','desc');
        }


        $pageStart = ($page - 1) * 10;
        $data= $this->db->from('shop')
            ->select('id,shopName,logo,minQuota,maxQuota,interest,abstract,hot')
            ->where('status',0)
            ->order_by('hot','desc')
            ->order_by('sort','asc')
            ->order_by('id','desc')
            ->limit(10,$pageStart)
            ->get()->result();
        foreach($data as $v){
            $v->logo= $this->cdn.$v->logo;
        }
        return $data;
    }
    function shopDetails($id,$userId=''){
        $data=$this->db->from('shop')->select('id,shopName,logo,abstract,minQuota,maxQuota,cycle,interest,num,condition,procedure,remarks,registerUrl,hot')->where('id',$id)->get()->row();
        $data->logo=$this->cdn.$data->logo;
        if($userId>0 && $data){
            $arr['sid']=$id;
            $arr['userId']=$userId;
            $this->db->insert('shopApply',$arr);
        }   
        return $data;
    }
    function newsDetails($id){
        $data=$this->db->from('news')->where('id',$id)->get()->row();
        if(!$data){
            return false;
        }
        $data->image= $this->cdn.$data->image;
        return $data;
    }
    function news($type,$page){

        if($type!=1 && $type!=2 && $type!=3){
            $type=1;
        }
        $pageStart = ($page - 1) * 10;
        $data= $this->db->from('news')
            ->select('id,title,image,hot,url,introduce,jump')
            ->where('status',0)
            ->where('type',$type)
            ->order_by('hot','desc')
            ->order_by('id','desc')
            ->limit(10,$pageStart)
            ->get()->result();
        foreach($data as $v){
            $v->image= $this->cdn.$v->image;
            if($v->jump!=1){
                $v->url=$this->cdn.'/home/newsDetails?id='.$v->id;
            }
            unset($v->id);
            unset($v->jump);
        }
        $card=$this->db->from('news')
            ->select('id,title,image,hot,url,introduce,jump')
            ->where('status',0)
            ->where('type',4)
            ->order_by('hot','desc')
            ->order_by('id','desc')
            ->get()->result();
        foreach($card as $v){
            $v->image= $this->cdn.$v->image;
            if($v->jump!=1){
                $v->url=$this->cdn.'/home/newsDetails?id='.$v->id;
            }
            unset($v->id);
            unset($v->jump);
        }
        return array('card'=>$card,'list'=>$data);
    }
    function search(){
        $data=$this->db->from('type')->select('id,title,pid')->where('pid',0)->get()->result();
        foreach($data as $v){

            $v->list=$this->db->from('type')->select('id,title')->where('pid',$v->id)->order_by('id','asc')->get()->result();
            array_unshift($v->list,array('id'=>'0','title'=>'不限'));
            unset($v->pid);
        }
        $arr=new stdClass();
        $arr->id='0';
        $arr->title='新品推荐';
        $arr->list=$this->db->from('type')->select('id,title')->where('pid!=',0)->group_by('title')->order_by('id','asc')->get()->result();
        array_unshift($arr->list,array('id'=>'0','title'=>'不限'));
        array_unshift($data,$arr);
        return $data;
    }
    function information(){
        $result = $this->db->from('config')->get()->row();
        $result->publicLogo=$this->cdn. $result->publicLogo;
        $result->publicImg=$this->cdn.$result->publicImg;
        $result->logo=$this->cdn.$result->logo;
        $result->image=$this->cdn.$result->image;
        $result->androidImage=$this->cdn.$result->androidImage;
        $result->feedback=$this->cdn.$result->feedback;
        unset($result->id);
        return $result;
    }
    function replace($type=1,$edition=''){
        $apk=$this->db->from('apk')->select('edition,depict,url,status')->where('type',$type)->order_by('id','desc')->get()->row();
        if($apk && $apk->edition!=$edition){
            if($type==1){
                $apk->url=$this->cdn.$apk->url;
            }
            return $apk;
        }
        return false;

    }
    function xiazaiurl(){
        $data=new stdClass();
        $data->android='';
        $data->ios='';
        //0-苹果 1-安卓
        $android=$this->db->from('apk')->select('url')->where('type',1)->order_by('id','desc')->get()->row();
        $ios=$this->db->from('apk')->select('url')->where('type',0)->order_by('id','desc')->get()->row();
        if($android){
            $data->android=$this->cdn.$android->url;
        }
        if($ios){
            $data->ios=$ios->url;
        }
        return $data;

    }
}