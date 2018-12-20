<?php

class Merchant extends AZ_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('Merchant_model', 'merchant');
        $this->load->model('User_model', 'user');
        $this->view->ver = mt_rand();
        $this->view->title = '商 户';
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
            $like['name']= $name;
        }
        $data=$this->merchant->queryAll($where, $like,'',$limit, $start);
        $pagetotle =$this->merchant->queryCount($where, $like);

        if($pagetotle > $limit){
            $this->load->library('page');
            $param = '';
            if($name){
                $param  .= '&name='.$name;
            }
            $pages  = $this->page->getPage($pagetotle,$limit,$param,BASEURL.'/merchant/index');
        }else{
            $pages  = '';
        }
        

        $this->view->pages = $pages;
        $this->view->data=isset($data)?$data:'';
    }
    function save(){
        $id=$this->input->get('id');
        $data=$this->merchant->queryOne(['id'=>$id]);
        $this->view->data=isset($data)?$data:'';
    }
    function add(){
        $post= $this->input->post();

        $re=$this->merchant->merchantAdd($post);
        if($re!==true){
            if($re==''){
                $re='失败';
            }
            echo '<script>alert("'.$re.'")</script>';exit;
        }
        echo '<script>parent.parent.window.location.href="/manage/merchant"</script>';exit;
    }
    function merchantStatus(){
        $id= $this->input->get('id');
        $status= $this->input->get('aid');
        if($status==0){
            $status=1;
        }else{
            $status=0;
        }
        $this->merchant->updates(['id'=>$id],['status'=>$status]);
        output_response(0);
    }
    function xinxi(){
        $id= $this->input->get('id');
        $limit = 10;
        $page = $this->input->get('page');
        if($page<1) {
            $page = 1;
        }
        $start = ($page - 1) * $limit;

        $name = $this->input->get('mobile');
        $starttime=$this->input->get('starttime');
        $endtime=$this->input->get('endtime');
        $like=[];
        $where['c.mid']=$id;
        if ($name) {
            $like['c.mobile']= $name;
        }
        if($starttime && $endtime){
            $where['c.createdTime >']=$starttime;
            $where['c.createdTime <']=$endtime;
        }
        $data=$this->merchant->channelregister($where, $like,'c.userId,c.mobile,c.createdTime',$limit, $start);
        foreach ($data as $v) {
            $v->name='';
            $v->card='';
            $v->age='';
            $re=$this->user->queryOne(['id'=>$v->userId]);
            if($re){
                $v->name=$re->realName;
                $v->card=$re->realCard;
                $v->age=$re->age==0?0:date('Y')-$re->age;
            }
        }
        
        $pagetotle =$this->merchant->channelregisterCount($where, $like);

        if($pagetotle > $limit){
            $this->load->library('page');
            $param = '';
            $param  .= '&id='.$id;
            if($name){
                $param  .= '&mobile='.$name;
            }
            $pages  = $this->page->getPage($pagetotle,$limit,$param,BASEURL.'/merchant/xinxi');
        }else{
            $pages  = '';
        }

        $this->view->pages = $pages;
        $this->view->data=isset($data)?$data:'';
    }
    function merchantDel(){
        $id=$this->input->get('id');
        $this->merchant->merchantDel($id);
        return true;
    }
    function tongji(){
        $page = $this->input->get('page');
        $name = $this->input->get('name');
        $page = $page < 1 ?  1 : $page;
        $pageSize = 10;
        $data=$this->merchant->tongji($page,$pageSize,$name);
        $pageCount=$data['count'];

        if($pageCount > $pageSize){
            $this->load->library('page');
            $param = '';
            $param  .= '&name='.$name;
            $pages  = $this->page->getPage($pageCount,$pageSize,$param,BASEURL.'/merchant/tongji');
        }else{
            $pages  = '';
        }
        $this->view->pages = $pages;
        $this->view->data= $data['data'];
        $info=$this->merchant->queryAll();
        $this->view->info = $info;
    }
    function exexl(){
        $id = $this->input->get('id');
        $mobile = $this->input->get('mobile');
        $starttime = $this->input->get('starttime');
        $endtime = $this->input->get('endtime');
        
        //新增的查询语句
        // echo $id;
        // echo $mobile;
        // echo $starttime;
        // echo $endtime;die;

        $where=[];
        $where['channelregister.mid'] = $id;
        if($starttime && $endtime){
            $where['channelregister.createdTime >']=$starttime;
            $where['channelregister.createdTime <']=$endtime;
        }
        $like=[];
        if ($mobile) {
            $like['channelregister.mobile']= $mobile;
        }
        $data=$this->merchant->channelregister_join($where, '*',$like);
        $dir = str_replace("manage","system",APPPATH)."libraries".DIRECTORY_SEPARATOR;
        include $dir.'PHPExcel/IOFactory.php';

        date_default_timezone_set('Europe/London');
        $objPHPExcel = new PHPExcel();

        /*以下是一些设置 ，什么作者  标题啊之类的*/
        $num = 1;
        $objPHPExcel->setActiveSheetIndex(0)//Excel的第A列，uid是你查出数组的键值，下面以此类推
                    ->setCellValue('A'.$num,'姓名')
                    ->setCellValue('B'.$num, '年龄')
                    ->setCellValue('C'.$num, '身份证号码')
                    ->setCellValue('D'.$num, '手机号')
                    ->setCellValue('E'.$num, '注册时间');
        /*以下就是对处理Excel里的数据， 横着取数据，主要是这一步，其他基本都不要改*/
        foreach($data as $v){
            $num++;
            $objPHPExcel->setActiveSheetIndex(0)//Excel的第A列，uid是你查出数组的键值，下面以此类推
                    ->setCellValue('A'.$num,$v->realName)
                    ->setCellValue('B'.$num, $v->age)
                    ->setCellValue('C'.$num, $v->realCard)
                    ->setCellValue('D'.$num, $v->mobile)
                    ->setCellValue('E'.$num, $v->createdTime);         
        }
        header('Content-Type: applicationnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.date('YmdHis').'.xls"');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007'); 
        $objWriter->save('php://output');
        exit;
        
    }

}