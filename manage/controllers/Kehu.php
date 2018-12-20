<?php

class Kehu extends AZ_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('Merchant_model', 'merchant');
        $this->load->model('User_model', 'user');
        $this->view->ver = mt_rand();
        $this->view->title = '用 户';
    }
    function index(){
        $id= $this->session->admin->id;
        $limit = 10;
        $page = $this->input->get('page');
        if($page<1) {
            $page = 1;
        }
        $start = ($page - 1) * $limit;

        $name = $this->input->get('mobile');
        $ks = $this->input->get('ks');
        $js = $this->input->get('js');
        $like=[];
        $where['c.mid']=$id;
        if ($name) {
            $like['c.mobile']= $name;
        }
        if($ks && $js){
            $where['c.createdTime >']=$ks;
            $where['c.createdTime <']=$js;
        }
        $data=$this->merchant->channelregister($where, $like,'c.mobile,c.createdTime',$limit, $start);
        $pagetotle =$this->merchant->channelregisterCount($where, $like);
        foreach ($data as $v) {
            $v->name='';
            $v->card='';
            $v->age='';
            $v->mobile=substr_replace($v->mobile,"****",3,4);
            $re=$this->user->queryOne(['id'=>$v->userId]);
            if($re){
                $v->name= $re->realName;
                $v->card=isset($re->realCard)?substr_replace($re->realCard,"********",6,8):'';
                $v->age=$re->age==0?0:date('Y')-$re->age;;
            }
        }
        
        if($pagetotle > $limit){
            $this->load->library('page');
            $param = '';
            if($name){
                $param  .= '&mobile='.$name;
            }
            $pages  = $this->page->getPage($pagetotle,$limit,$param,BASEURL.'/kehu');
        }else{
            $pages  = '';
        }

        $this->view->p = $page;
        $this->view->pages = $pages;
        $this->view->data=isset($data)?$data:'';
    }
    public function logout()
    {
        $this->session->unset_userdata('admin');
        session_destroy();
        $this->session->sess_destroy();
        $this->redirect(BASEURL.'/home/login');
    }
    function mima(){
        $this->view->title = '修改密码';

        $pa=$this->input->post('pa');
        $pass=$this->input->post('pass');
        $pass1=$this->input->post('pass1');
        if($pa){
            if(strlen($pass)>=6 && strlen($pass)<=15){
                $mi=$this->merchant->queryOne(['id'=>$this->session->admin->id]);
                if($mi){
                    if($mi->password==md5($pa)){
                        if($pass!=$pass1){
                            echo '<script>var a="两次输入的密码不同";</script>';
                        }else{
                            $re=$this->merchant->updates(['id'=>$this->session->admin->id],['password'=>md5($pass)]);
                            if($re){
                                echo '<script>var a="修改成功";</script>';
                            }else{
                                echo '<script>var a="修改失败";</script>';
                            }
                        }
                    }else{
                        echo '<script>var a="旧密码错误，请重新输入";</script>';
                    }
                }else{
                    echo '<script>var a="请重新登陆";</script>';
                }
            }else{
                echo '<script>var a="密码长度为6-15位，请重新输入 ";</script>';
            }

        }
    }
}