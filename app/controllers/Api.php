<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends AZ_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('User_model','user');
        $this->load->model('Home_model','home');
        $this->cdn = !empty($this->cdn) ? $this->cdn : 'http://' . $_SERVER['HTTP_HOST'];
    }
    function renzheng(){
        $id=$this->input->get('id');
        $data=$this->home->information();
        if($data->certification==1 && isset($id)){
            $re=$this->user->certification($id);
            if($re){
               $data->certification=0;
            }
        }
        if($data->certification==1){
            output_response(0,['certification'=>1],'弹框');
        }
        output_response(0,['certification'=>0],'不弹框');
    }
    function replace(){
        $type = $this->input->get('type');//0-苹果 1-安卓
        $edition = $this->input->get('edition');//版本号
        $type=$type==1?1:0;
        $date=$this->home->replace($type,$edition);
        if($date){
            output_response(0,$date);
        }
        output_response(1,[],'不用更新');
    }
    //首页
    function index(){
        $banner=$this->home->banner();
        $adv=$this->home->adv(2);
        $shop=$this->home->shop();
        $data['banner']=$banner;
        $data['list']=$this->home->search();
        $data['adv']=$adv;
        $data['shop']=$shop;
        output_response(0,$data);
    }
    public function login(){
        $mobile = $this->input->post('mobile');
        $password = $this->input->post('password');
       //  $mobile ='123456';$password = 123456;
        if(!$mobile || !$password) {
            output_response(1,[],'请输入手机号或密码！');
        }
        $data=$this->user->login($mobile,$password);
        if($data){
            output_response(0,$data,'登陆成功');
        }else{
            output_response(1,[],'登陆失败');
        }
    }
    public function quickLogin(){
        $mobile = $this->input->post('mobile');
        $validate = $this->input->post('validate');
        $channel = $this->input->post('channel');
        $code = $this->input->post('code');

        if(!$channel){
            $channel =9;
        }
        if(!$mobile || !$validate) {
            output_response(1,[],'请输入手机号或验证码！');
        }
        $this->yanzheng($mobile,$validate,3);
        $data=$data=$this->user->quickLogin($mobile,$channel,$code);
        if($data){
            output_response(0,$data,'登陆成功');
        }else{
            output_response(1,[],'登陆失败');
        }

    }
    public function quickLogin1(){
        $mobile = $this->input->post('mobile');
        $validate = $this->input->post('validate');
        $channel = $this->input->post('channel');
        $code = $this->input->post('code');
        $password = $this->input->post('password');

        if(!$channel){
            $channel =9;
        }
        if(!$mobile || !$validate) {
            output_response(1,[],'请输入手机号或验证码！');
        }
        $this->yanzheng($mobile,$validate,1);
        $data=$data=$this->user->quickLogin($mobile,$channel,true,$code,$password);
        if($data){
            output_response(0,$data,'登陆成功');
        }else{
            output_response(1,[],'登陆失败');
        }

    }
    //注册
    public function register(){
        $name = $this->input->post('name');
        $sex = $this->input->post('sex');
        $mobile = $this->input->post('mobile');
        $password = $this->input->post('password');
        $validate = $this->input->post('validate');
        $channel = $this->input->post('channel');

        $sex=$sex==1?1:0;
        !$name?output_response(1,[],'姓名不能为空！'):'';
        !$mobile?output_response(1,[],'手机不能为空！'):'';
        !$password?output_response(1,[],'密码不能为空！'):'';
        !$validate?output_response(1,[],'验证码不能为空！'):'';
        //用正则表达式函数进行判断
        if(!preg_match("/^1[3456789]{1}\d{9}$/",$mobile)){
            output_response(1,[],'手机号格式错误！');
        }
        if(!preg_match("/^[^\s]{6,15}$/",$password)){
           // output_response(1,[],'密码必须为6-15位，不能有空格！');
        }
        $result=$this->user->mobileProving($mobile);
        !$result?output_response(1,[],'该手机已经注册过！'):'';
        $this->yanzheng($mobile,$validate,1);
        $re=$this->user->register($name,$sex,$mobile,$password,$channel);
        if($re){
            output_response(0,$re,'注册成功');
        }
        output_response(1,[],'注册失败');
    }
    //发验证码
    public function getVerify(){
        $mobile = $this->input->get('mobile');
        $type = $this->input->get('type');//1-注册 2-忘记、修改密码  3-短信登陆
        if(!preg_match('/^((1[3|4|5|6|7|8|9])[0-9]{9})$/',$mobile)){
            output_response(1,[],'手机号格式错误!');
        }
        if(!isset($type)){
            $type=1;
        }
        if($type==1){
            $result=$this->user->mobileProving($mobile);
            !$result?output_response(1,[],'该手机已经注册过！'):'';
        }

        $rand=rand(000000,999999);
        //发送短信
        $data['mobile']=$mobile;
        $data['validate']=$rand;
        $data['time']=time();
        $data['type']=$type;

        
        $result=$this->sendSMS($mobile,'【借多多】您好，您正在板凳借钱平台操作，本次验证码是'.$rand.'，5分钟有效。如非本人操作请忽略！');

        if(!is_null(json_decode($result))){
            $output=json_decode($result,true);
            if(isset($output['code'])  && $output['code']=='0'){
            	$this->user->valiInsert($data);
                output_response(0);
            }
        }
        output_response(1,$result,'发送失败');
    }
    //验证码验证
    function yanzheng($mobile,$verify,$type=1){
        $where['mobile']=$mobile;
        $where['validate']=$verify;
        $where['time >']=time()-300;
        $where['type']=$type;
        $where['status']=0;
        $this->user->valiyanzheng($where);
        return true;
    }
    //忘记密码
    public function changeForget(){
        $mobile = $this->input->post('mobile');
        $password = $this->input->post('password');
        $validate = $this->input->post('validate');

        if(!$mobile || !$password) {
            output_response(1,[],'请输入用户名或密码！');
        }
        if($validate==''){
            output_response(1,[],'请输入验证码！');
        }
        $this->yanzheng($mobile,$validate,2);

        $this->user->changeForget($mobile,$password);
        output_response(0,[],'修改成功');
    }
    function adv(){
        $adv=$this->home->adv();
        $adv=json_encode($adv);
        $adv=json_decode($adv,true);
        if($adv){
            output_response(0,$adv);
        }
        output_response(0,array('img'=>$this->cdn.'/img/qidong.png','url'=>''));
    }
    function search(){
        $data=$this->home->search();
        output_response(0,$data);
    }
    function sort(){
        $data=array(
            array('id'=>'1','title'=> '综合排序'),
            array('id'=>'2','title'=> '成功率高'),
            array('id'=>'3','title'=> '利率最低'),
            array('id'=>'4','title'=> '额度最高'),
            array('id'=>'5','title'=> '放款最快'),
        );
        $data=array(
            array('id'=>'1','title'=> '综合排序'),
            array('id'=>'2','title'=> '时间正序'),
            array('id'=>'3','title'=> '时间到序'),
            array('id'=>'4','title'=> '热门排行'),
        );
        output_response(0,$data);
    }

    function shop(){
        $searchId = $this->input->get('searchId');
        $priceId = $this->input->get('priceId');
        $sortId = $this->input->get('sortId');
        $page = $this->input->get('page');
        $page = $page < 1 ?  1 : $page;

        $data=$this->home->shopList($searchId,$priceId,$sortId,$page);
        if(!$data){
            output_response1(0,$data);
        }
        output_response(0,$data);
    }
    function shopDetails(){
        $id=$this->input->get('id');
        $userId=$this->input->get('userId');
        if($userId==''){
            $userId=0;
        }
        if($id<1){
            output_response(1,[],'id错误');
        }
        $data=$this->home->shopDetails($id,$userId);
        if($data->registerUrl!=''){
            $data->registerUrl=$this->cdn.'/api/tiao?id='.$userId.'&sid='.$id;
        }
        output_response(0,$data);
    }
    function news(){
        $type = $this->input->get('type');
        $page = $this->input->get('page');
        $page = $page < 1 ?  1 : $page;
        $data=$this->home->news($type,$page);
        output_response(0,$data);
    }
    function tiao(){
        $id=$this->input->get('id');
        $sid=$this->input->get('sid');
        $data['userId']=$id;
        $data['shopId']=$sid;
        $this->db->insert('register',$data);
        $arr['sid']=$sid;
        $arr['userId']=$id;
        $arr['type']=1;
        if($id>0 && $sid>0){
            $this->db->insert('shopApply',$arr);
        }
       
        $shop=$this->db->from('shop')->select('registerUrl')->where('id',$sid)->get()->row();
        if(!$shop){echo '信息错误';exit;}
        $url=$shop->registerUrl;
        header("Location:".$url);exit;
    }
    //注册是否需要图文验证码
    function sms(){
        $data=$this->home->information();
        if($data->sms!=1){
            $data->sms=2;
        }
        output_response(0,array('type'=>$data->sms));
    }
    function information(){
        $id=$this->input->get('id');
        $data=$this->home->information();
        if($data->certification==1 && isset($id)){
            $re=$this->user->certification($id);
            if($re){
               $data->certification=0;
            }
        }
        unset($data->sms);
        output_response(0,$data);
    }
    function certification(){
        $id=$this->input->get('id');
         $data=$this->home->information();
        if($data->certification==1 && isset($id)){
            $re=$this->user->certification($id);
            if($re){
               $data->certification=0;
            }
        }
        if($data->certification==1){
            output_response(1,['certification'=>1],'未认证');
        }
        output_response(0,['certification'=>0]);
    }
    function real(){
        $id=$this->input->post('id');
        $name=$this->input->post('name');
        $card=$this->input->post('card');
        
        if(isset($name) && isset($card) && isset($id)){
            $scardyan=$this->validateIdCard($card);
            if(!$scardyan){
                output_response(1,[],'身份证号格式错误');
            }
            $re=$this->user->real($id,$name,$card);
            if($re){
                output_response(0);
            }else{
                output_response(1,[],'此身份证已认证过');
            }
        }
        output_response(1,[],'信息错误');
    }
    function validateIdCard($value){
        if (!preg_match('/^\d{17}[0-9xX]$/', $value)) { //基本格式校验
            return false;
        }
        $parsed = date_parse(substr($value, 6, 8));
        if (!(isset($parsed['warning_count']) && $parsed['warning_count'] == 0)) { //年月日位校验
            return false;
        }
        $base = substr($value, 0, 17);
        $factor = [7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2];
        $tokens = ['1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2'];
        $checkSum = 0;
        for ($i=0; $i<17; $i++) {
            $checkSum += intval(substr($base, $i, 1)) * $factor[$i];
        }
        $mod = $checkSum % 11;
        $token = $tokens[$mod];
        $lastChar = strtoupper(substr($value, 17, 1));
        return ($lastChar === $token); //最后一位校验位校验
    }
    function text(){
        $data=array(
            'title'=>'新闻',//贷款
            'top'=>'新闻Top5',//贷超Top5
            'strategy'=>'攻略',//贷款攻略
            'card'=>'新闻',//快速办理信用卡
            'tips'=>'新闻',//同时申请三个及以上贷款,可大大提高贷款成功率
            'register'=>'我已阅读并同意《服务协议》',//我已阅读并同意《秒下钱包贷款服务协议》
            );
         $data=array(
            'title'=>'贷款',//贷款
            'top'=>'贷超Top5',//贷超Top5
            'strategy'=>'贷款攻略',//贷款攻略
            'card'=>'快速办理信用卡',//快速办理信用卡
            'tips'=>'同时申请三个及以上贷款,可大大提高贷款成功率',//同时申请三个及以上贷款,可大大提高贷款成功率
            'register'=>'我已阅读并同意《秒下钱包贷款服务协议》',//我已阅读并同意《秒下钱包贷款服务协议》
            );
        output_response(0,$data);
    }
}