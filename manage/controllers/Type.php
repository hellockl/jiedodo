<?php

class Type extends AZ_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('Type_model', 'type');
        $this->view->ver = mt_rand();
        $this->view->title = '类 型';
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
        $where['pid']=0;
        if ($name) {
            $like['title']= $name;
        }
        $data=$this->type->queryAll($where, $like,'',$limit, $start,0,0,'id asc');
        $pagetotle =$this->type->queryCount($where, $like);

        if($pagetotle > $limit){
            $this->load->library('page');
            $param = '';
            if($name){
                $param  .= '&name='.$name;
            }
            $pages  = $this->page->getPage($pagetotle,$limit,$param,BASEURL.'/Merchant/index');
        }else{
            $pages  = '';
        }

        $this->view->pages = $pages;
        $this->view->data=isset($data)?$data:'';
    }
    function details(){
        $id = $this->input->get('id');
        if($id<1){
            output_response(0,[]);
        }
        $data=$this->type->details($id);
        output_response(0,$data);
    }
    function save(){
        $post= $this->input->post();
        $data['title']=$post['title'];
        if($post['id']=='' || $post['id']=='undefined'){
            $id=$this->type->inserts($data);
        }else{
            $this->type->updates(['id'=>$post['id']],$data);
            $id=$post['id'];
        }

        foreach($post['day'] as $k=>$v){
            unset($brr);
            $brr['pid']=$id;
            $brr['title']=$v;
            $arr[]=$brr;
        }
        $this->type->deletes(['pid'=>$id]);
        $this->type->insert_batchs($arr);

        echo '<script>parent.location.reload();</script>';exit;
    }

}