<?php

class Card extends AZ_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('News_model', 'news');
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
        $where['type']=4;
        if ($name) {
            $like['title']= $name;
        }
        $data=$this->news->userAll($where, $like, $limit, $start);
        $pagetotle =$this->news->userCount($where, $like);

        if($pagetotle > $limit){
            $this->load->library('page');
            $param = '';
            if($name){
                $param  .= '&name='.$name;
            }
            $pages  = $this->page->getPage($pagetotle,$limit,$param,BASEURL.'/user/index');
        }else{
            $pages  = '';
        }

        $this->load->view('news/index',array('title'=>'信用卡','data'=>$data,'pages'=>$pages));
    }


}