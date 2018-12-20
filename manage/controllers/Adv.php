<?php

class Adv extends AZ_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('Banner_model', 'banner');
        $this->view->ver = mt_rand();

    }
    public function index(){
        $limit = 10;
        $page = $this->input->get('page');
        if($page<1) {
            $page = 1;
        }
        $start = ($page - 1) * $limit;

        $name = $this->input->get('name');
        $like=[];
        $where['type!=']=0;
        if ($name) {
            $like['title']= $name;
        }
        $data=$this->banner->queryAll($where, $like,'*', $limit, $start,array('status'=>'desc','id'=>'desc'));
        $pagetotle =$this->banner->queryCount($where, $like);

        if($pagetotle > $limit){
            $this->load->library('page');
            $param = '';
            if($name){
                $param  .= '&name='.$name;
            }
            $pages  = $this->page->getPage($pagetotle,$limit,$param,BASEURL.'/banner/index');
        }else{
            $pages  = '';
        }
        $this->load->view('banner/index',array('title'=>'广 告','data'=>$data,'pages'=>$pages));
    }

}