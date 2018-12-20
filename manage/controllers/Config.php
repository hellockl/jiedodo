<?php

class Config extends AZ_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('Admin_model', 'admin');
        $this->view->ver = mt_rand();
        $this->view->title = '配 置';
    }

    public function index(){
        $data=$this->admin->config();
        $this->view->data=isset($data)?$data:'';
    }
    function save(){
        $post=$this->input->post();
        if($post['image']!=''){
            $data['publicLogo']=$post['image'];
        }
        if($post['image1']!=''){
            $data['publicImg']=$post['image1'];
        }
        if($post['image2']!=''){
            $data['logo']=$post['image2'];
        }
        if($post['image3']!=''){
            $data['image']=$post['image3'];
        }
        if($post['image4']!=''){
            $data['androidImage']=$post['image4'];
        }
        if($post['image5']!=''){
            $data['feedback']=$post['image5'];
        }
        if($post['password']!=''){
            $arr['password']=md5($post['password']);
            $this->admin->updates(['id'=>1],$arr);
        }
        $data['sms']=isset($post['sms']) && $post['sms']==1?1:2;
        $data['publicTitle']=$post['publicTitle'];
        $data['serviceMobile']=$post['serviceMobile'];
        $data['title']=$post['title'];
        $data['content']=$post['content'];
        $data['iosDownload']=$post['iosDownload'];
        $data['androidDownload']=$post['androidDownload'];
        $this->admin->configUpdate($data);
        echo '<script>parent.window.location.href="/manage/config";//parent.location.reload();parent.</script>';exit;
    }
}