<?php
class Home extends AZ_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->view->ver = mt_rand();
		$this->load->model('User_model', 'user');
		$this->load->model('Admin_model', 'admin');
		$this->view->title = '首 页';
	}
	public function index()
	{
		$starttime=$this->input->get('starttime');
		$endtime=$this->input->get('endtime');
		$where='';
		if($starttime && $endtime){
			$where['createdTime >']=$starttime;
			$where['createdTime <']=$endtime;
		}
		$data=$this->user->home($where);
		$this->view->data=isset($data)?$data:'';
	}

	/**
	 * 登录
	 */
	public function login()
	{
		$login_info = $this->input->cookie('adminInfo');
		if($login_info)
			$login_info = unserialize($login_info);
		else
			$login_info = array(
				'username' => '',
				'password' => ''
			);
		$information=$this->admin->config();
		$this->view->logo=isset($information->logo)?$information->logo:'';
		$this->view->username = $login_info['username'];
		$this->view->password = $login_info['password'];
	}

		/**
	 * 登录动作
	 */
	public function sign()
	{
		$username = $this->input->post('username');
		$password = $this->input->post('password');
		if($username==''){
            output_response(1,['code'=>1,'str'=>'账号不能为空!']);
		}
        if($password==''){
            output_response(1,['code'=>2,'str'=>'密码不能为空!']);
        }
		$this->load->library('extend');
		$this->load->model('admin_model', 'admin');

		$pwd = md5($password);

		$user_info = $this->admin->getAdminInfo($username,$pwd);
		if(!$user_info){
			$this->load->model('Merchant_model', 'merchant');
			$user_info=$this->merchant->getAdminInfo($username,$pwd);
		}
		//用户名或密码错误
		if(empty($user_info)){
            output_response(1,['code'=>3,'str'=>'账号/密码错误,请重新登陆!']);
		}
		if($user_info->status==2){
			output_response(1,['code'=>3,'str'=>'此用户被禁用，请联系管理员!']);
		}
		$this->session->set_userdata('admin', $user_info);
        output_response(0);
	}
	public function logout()
	{
		$this->session->unset_userdata('admin');
		session_destroy();
		$this->session->sess_destroy();
		$this->redirect(BASEURL.'/home/login');
	}
}
