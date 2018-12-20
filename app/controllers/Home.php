<?php
class Home extends AZ_Controller
{
    public function __construct()
    {
        parent::__construct();
       
        $this->load->model('Home_model', 'home');
    }
     
    function index($code=''){
        // $code=$this->input->get('code');
        $data=$this->home->information();
        $data=array(
            'title'=>$data->publicTitle,
            'code'=>$code
        );
        $this->load->view($this->router->class . '/' . $this->router->method . '.php',$data);
       
    }
    function android(){
        $data=$this->home->information();
       
        $data->url=$this->home->xiazaiurl()->android;
        $this->load->view('url',$data);
    }

    function newsDetails(){
        $id=$this->input->get('id');
        $data=$this->home->newsDetails($id);
        $this->load->view('news',array('data'=>$data));
    }
    function registerPrivacy(){
        $this->load->view('registerPrivacy');
    }
    function instructions(){
        $this->load->view('privacy');
    }
    function privacy(){
        $this->load->view('privacy');
    }
    function especially(){
        $this->load->view('especially');
    }
    
}