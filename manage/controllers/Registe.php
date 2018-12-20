<?php

class Registe extends AZ_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('Shop_model', 'shop');
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
        $where=[];
        if ($name) {
            $like['shopName']= $name;
        }

        $data=$this->shop->shopAll($where, $like, $limit, $start);
        $pagetotle = $this->shop->shopCount($where, $like);

        if($pagetotle > $limit){
            $this->load->library('page');
            $param = '';
            if($name){
                $param  .= '&name='.$name;
            }
            $pages  = $this->page->getPage($pagetotle,$limit,$param,BASEURL.'/registe/index');
        }else{
            $pages  = '';
        }

        $this->view->pages = $pages;
        $this->view->data=isset($data)?$data:'';
        $this->view->title = '公 司';
    }
    function save(){
        $id=$this->input->get('id');
        $data=$this->shop->queryOne(['id'=>$id]);
        $this->view->data=isset($data)?$data:'';
    }
    function add(){
        $post= $this->input->post();
        if($post['logo']!=''){
            $post['logo']=base64_upload($post['logo']);
        }
        $re=$this->shop->shopAdd($post);
        if($re!==true){
            if($re==''){
                $re='失败';
            }
            echo '<script>alert("'.$re.'")</script>';exit;
        }
        echo '<script>parent.parent.window.location.href="/manage/registe"</script>';exit;
    }
    function registeDel(){
        $id= $this->input->get('id');
        $this->shop->deletes(['id'=>$id]);
        output_response(0);
    }
    function registeStatus(){
         $id= $this->input->get('id');
         $status= $this->input->get('status');
        $this->shop->updates(['id'=>$id],['status'=>$status]);
        output_response(0);

    }
    function shenqing(){
        $id= $this->input->get('id');
        $data=$this->shop->shenqing($id);
        $this->view->data=isset($data)?$data:'';
    }
    function registetype(){
        $data=$this->shop->registetype();
        output_response(0,$data);
    }
    function tongji(){
        $id=$this->input->get('id');
        $starttime=$this->input->get('starttime');
        $endtime=$this->input->get('endtime');
        $data=$this->shop->tongji($id,$starttime,$endtime);
        $this->view->data=$data;
    }
    function advance(){
        $id=$this->input->get('id');
        $data=$this->shop->advance($id);
        $count=$this->shop->advanceCount($id);
        $this->view->data=$data;
        $this->view->count=$count;
    }
    function advanceAdd(){
        $id=$this->input->post('id');
        $money=$this->input->post('money');
        if($id){
            if($money>0)
                $this->shop->advanceAdd($id,$money);
            echo '<script>parent.window.location.reload(); </script>';exit;
        }
    }
    function advanceDel(){
        $id=$this->input->get('id');
        $this->shop->advanceDel($id);
        return true;
    }
}