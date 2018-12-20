<?php

class News extends AZ_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('News_model', 'news');
        $this->view->ver = mt_rand();
        $this->view->title = '攻 略';
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
        $where['type!=']=4;
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

        $this->view->pages = $pages;
        $this->view->data=isset($data)?$data:'';
    }
    function save(){
        $id=$this->input->get('id');
        $data=$this->news->queryOne(['id'=>$id]);
        $this->view->data=isset($data)?$data:'';
    }
    function add(){
        $post= $this->input->post();
        $re=$this->news->newsAdd($post);
        if($re!==true){
            if($re==''){
                $re='失败';
            }
            echo '<script>alert("'.$re.'")</script>';exit;
        }
        echo '<script>parent.location.reload();</script>';exit;
    }
    function newsDel(){
        $id= $this->input->get('id');
        $this->news->deletes(['id'=>$id]);
        output_response(0);
    }
    function newStatus(){
        $id=$this->input->post('id');
        $status=$this->input->post('status');
        $this->news->updates(['id'=>$id],['status'=>$status==1?1:0]);
        output_response(0);
    }

}