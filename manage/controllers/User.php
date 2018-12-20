<?php

class User extends AZ_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('User_model', 'user');

        $this->view->ver = mt_rand();
        $this->view->title = '人 员';
    }

    public function index(){
        $limit = 10;
        $page = $this->input->get('page');
        if($page<1) {
            $page = 1;
        }
        $start = ($page - 1) * $limit;

        $name = $this->input->get('name');
        $lai = $this->input->get('lai');
        $starttime=$this->input->get('starttime');
        $endtime=$this->input->get('endtime');
        $where=[];
        if($starttime && $endtime){
            $where['createdTime >']=$starttime;
            $where['createdTime <']=$endtime;
        }
        $like=[];

        if ($name) {
            $like['mobile']= $name;
        }
        $data=$this->user->userAll($where, $like, $limit, $start,$lai);
        $pagetotle =$this->user->userCount($where, $like,$lai);

        if($pagetotle > $limit){
            $this->load->library('page');
            $param = '';
            $param  .= '&name='.$name;
            $param  .= '&lai='.$lai;
            $param  .= '&starttime='.$starttime;
            $param  .= '&endtime='.$endtime;
            $pages  = $this->page->getPage($pagetotle,$limit,$param,BASEURL.'/user/index');
        }else{
            $pages  = '';
        }

        $this->view->pages = $pages;
        $this->view->data=isset($data)?$data:'';
        $this->load->model('Merchant_model', 'merchant');
        $info=$this->merchant->queryAll();
        $this->view->info = $info;

    }
    function shenqing(){
        $id=$this->input->get('id');
        $data=$this->user->registerList($id);
        $this->view->data=isset($data)?$data:'';
    }
    function fen(){
        $id=$this->input->get('id');
        $starttime=$this->input->get('starttime');
        $endtime=$this->input->get('endtime');
        $data=$this->user->userFen($id,$starttime,$endtime);
        $this->view->data=$data;
    }
}