<?php

class Banner extends AZ_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('Banner_model', 'banner');
        $this->view->ver = mt_rand();
        $this->view->title = '轮	 播';
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
        $where['type']=0;
        if ($name) {
            $like['title']= $name;
        }
        $data=$this->banner->queryAll($where, $like,'*', $limit, $start);
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

        $this->view->pages = $pages;
        $this->view->data=isset($data)?$data:'';
    }
    function bannerStatus(){
        $id=$this->input->post('id');
        $status=$this->input->post('status');
        $this->banner->updates(['id'=>$id],['status'=>$status==1?1:0]);
        output_response(0);
    }
    function add(){
        $post=$this->input->post();
        if($post){
            $post['type']=isset($post['type'])?$post['type']:0;
            $data['title']=$post['title'];
            $data['url']=$post['url'];
            $data['img'] = $post['image'];
            $data['type'] = $post['type'];

            if($post['id']!=''){
                $this->banner->updates(['id'=>$post['id']],$data);
            }else{
                $this->banner->inserts($data);
            }

            echo '<script>parent.location.reload();//parent.parent.window.location.href="/manage/banner"</script>';exit;
        }
        $id=$this->input->get('id');
        $data=$this->banner->queryOne(['id'=>$id]);
        $this->view->data=isset($data)?$data:'';
    }
    function bannerDel(){
        $id=$this->input->get('id');
        $this->banner->deletes(['id'=>$id]);
        output_response(0);
    }
}