<?php

class Edition extends AZ_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('Apk_model', 'apk');
        $this->view->title='版 本';
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
        $where['type']=1;
        if ($name) {
            $like['edition']= $name;
        }
        $data=$this->apk->queryAll($where, $like,'*', $limit, $start);
        $pagetotle =$this->apk->queryCount($where, $like);

        if($pagetotle > $limit){
            $this->load->library('page');
            $param = '';
            if($name){
                $param  .= '&name='.$name;
            }
            $pages  = $this->page->getPage($pagetotle,$limit,$param,BASEURL.'/edition/index');
        }else{
            $pages  = '';
        }
        
        $this->view->pages=$pages;
        $this->view->data=$data;
    }
    function save(){
        $post=$this->input->post();
        $data['type']=$post['type']==1?1:0;//0-苹果 1-安卓
        $data['edition']=$post['edition'];
        $data['url']=$data['type']==1?$post['apk']:$post['ios'];
        $data['depict'] = $post['depict'];
        $data['status'] = isset($post['status'])?1:0;
        if($post['id']!=''){
            $this->apk->updates(['id'=>$post['id']],$data);
        }else{
            $this->apk->inserts($data);
        }
         echo '<script>parent.location.reload();</script>';exit;
    }
    function del(){
        $id=$this->input->get('id');
        $this->apk->deletes(['id'=>$id]);
        output_response(0);
    }
    public function ios(){
        $limit = 10;
        $page = $this->input->get('page');
        if($page<1) {
            $page = 1;
        }
        $start = ($page - 1) * $limit;

        $name = $this->input->get('name');
        $like=[];
        $where['type']=0;
        if ($name) {
            $like['edition']= $name;
        }
        $data=$this->apk->queryAll($where, $like,'*', $limit, $start);
        $pagetotle =$this->apk->queryCount($where, $like);

        if($pagetotle > $limit){
            $this->load->library('page');
            $param = '';
            if($name){
                $param  .= '&name='.$name;
            }
            $pages  = $this->page->getPage($pagetotle,$limit,$param,BASEURL.'/edition/index');
        }else{
            $pages  = '';
        }
        $this->load->view('edition/index',array('title'=>'版 本','data'=>$data,'pages'=>$pages,'ios'=>1));
    }


}