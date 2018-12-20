<?php

class Channel extends AZ_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('Channel_model', 'channel');
        $this->view->ver = mt_rand();
    }

    public function index(){
        $starttime=$this->input->get('starttime');
        $endtime=$this->input->get('endtime');
        $data=$this->channel->dayUser($starttime,$endtime,1);
        $this->load->view('channel/dayUser',array('data'=>$data,'title'=>'日新增用户'));

    }
    public function monthUser(){
        $nian=$this->input->get('nian');
        $data=$this->channel->monthUser($nian);
        $shi=$this->channel->shi();
        $this->view->title='月新增用户数';
        $this->view->data=$data;
        $this->view->shi=$shi;
    }
    public function dayApply(){
        $starttime=$this->input->get('starttime');
        $endtime=$this->input->get('endtime');
        $data=$this->channel->dayUser($starttime,$endtime,2);
        $this->load->view('channel/dayUser',array('data'=>$data,'title'=>'日申请次数'));

    }
    public function dayapplyUser(){
        $starttime=$this->input->get('starttime');
        $endtime=$this->input->get('endtime');
        $data=$this->channel->dayUser($starttime,$endtime,3);
        $this->load->view('channel/dayUser',array('data'=>$data,'title'=>'日申请用户'));

    }
    public function monthApply(){
        $nian=$this->input->get('nian');
        $shi=$this->channel->shi();
        $data=$this->channel->monthUser($nian,2);
        $this->load->view('channel/monthUser',array('shi'=>$shi,'data'=>$data,'title'=>'月申请次数'));

    }
    public function monthapplyUser(){
        $nian=$this->input->get('nian');
        $shi=$this->channel->shi();
        $data=$this->channel->monthUser($nian,3);
        $this->load->view('channel/monthUser',array('shi'=>$shi,'data'=>$data,'title'=>'月申请用户'));
    }
    public function dayFen(){
        $starttime=$this->input->get('starttime');
        $endtime=$this->input->get('endtime');
        $data=$this->channel->dayFen($starttime,$endtime);
        $this->load->view('channel/dayUser',array('data'=>$data,'title'=>'日分系数'));
    }
}