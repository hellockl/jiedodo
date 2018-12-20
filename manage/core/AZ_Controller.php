<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class AZ_Controller extends CI_Controller
{
	public $viewData;
	public $useView = true;		//是否加载view 文件
	public $useModel = true;	//是否默认加载model
	public $pageSize = 10;

	function __construct(){
		//关于memcached常量定义
		define('MEMCACHESERVER','127.0.0.1');
		define('MEMCACHEPORT','11211');
		parent::__construct();
		$this->load->library('session');
		$this->_init();
		//$this->loadModel();
		$this->load->library('view');

		$noOauthList['method'] = array('getCode');
		$noOauthList['class'] = array();
		if(isset($this->session->admin->code) && $this->router->class!='kehu' ){
				$this->redirect('/manage/kehu');
		}

		if(!in_array($this->router->method,$noOauthList['method']) &&
			!in_array($this->router->class,$noOauthList['class'])){
		}
        $this->view->class = $this->router->class;
        $this->view->method = $this->router->method;
	}

	function _init()
	{
		//uploadify上传页面不验证
		//if($this->uri->segment(2) == 'albumUpload') return;
		//检测是否登录
		if(!$this->session->has_userdata('admin'))
		{
			if($this->router->method != 'login' && $this->router->method != 'sign'  && $this->router->method != 'getVerify'  && $this->router->method != 'activation')
				header('location:'.BASEURL.'/home/login');
		}else{
			if($this->router->method == 'login'){
				header('location:'.BASEURL.$this->class);
			}
		}

	}


	/**
	 * ramap
	 *
	 */
	public function _remap() {
		$args = func_get_args();
		if(isset($args[0])) {
			$method = $args[0];
			@call_user_func_array(array($this, $method), isset($args[1]) ? $args[1] : array());
			//if($method != 'view' && substr($method,0,1) != '_')
			$this->loadView();
		}
	}

	/**
	 * 默认加载view
	 */
	public function loadView()
	{

		$viewName = $this->router->class . '_' . $this->router->method . '.php';
		$viewName2 = $this->router->class . '/' . $this->router->method . '.php';

		//使用view 的话加载view， this->view 为 true 说明使用view false 不使用
		if ($this->useView)
		{
			if(file_exists(APPPATH . 'views/' . $viewName))
				$this->load->view($viewName, $this->viewData);
			elseif(file_exists(APPPATH . 'views/' . $viewName2))
				$this->load->view($viewName2, $this->viewData);

		}
	}

	public function create_guid(){
        $microTime = microtime();
        list($a_dec, $a_sec) = explode(" ", $microTime);
        $dec_hex = dechex($a_dec* 1000000);
        $sec_hex = dechex($a_sec);
        $this->ensure_length($dec_hex, 5);
        $this->ensure_length($sec_hex, 6);
        $guid = "";
        $guid .= $dec_hex;
        $guid .= $this->create_guid_section(3);
        // $guid .= '-';
        $guid .= $this->create_guid_section(4);
        // $guid .= '-';
        $guid .= $this->create_guid_section(4);
        // $guid .= '-';
        $guid .= $this->create_guid_section(4);
        // $guid .= '-';
        $guid .= $sec_hex;
        $guid .= $this->create_guid_section(6);
        return $guid;
    }
     
    private function ensure_length(&$string, $length){   
        $strlen = strlen($string);   
        if($strlen < $length)   
        {   
            $string = str_pad($string,$length,"0");   
        }   
        else if($strlen > $length)   
        {   
            $string = substr($string, 0, $length);   
        }  
    }

	function uploadExl($file)
	{
		$file = urldecode($file);
		//print_r($_FILES);
		if (empty($_FILES)) {
			show_404();
		}
		$name = $_FILES[$file]['name'];
		//print_r($_FILES[$file]);
		$arr = explode(".", $name);
		$n = count($arr) - 1;
		$extend = $arr[$n];
		//$fn = (isset($_SERVER['HTTP_X_FILENAME']) ? $_SERVER['HTTP_X_FILENAME'] : false);
		$docPath = $_SERVER['DOCUMENT_ROOT'];
		$dir = '/img/upload/manage/';
//		$dir = $dir.$this->session->manage->officialId.'/';
//		$dir = $dir.$this->session->manage->officialAccountId.'/';
		$dir = $dir.date("Ymd");
		$filename = date("dHis") . rand(1000, 9999) . '.' . $extend;
		$uploadFile = $dir .'/'. $filename;
		$this->_createDir($uploadFile);
		$fullPath = $docPath.$dir;

		$config['upload_path'] = $fullPath;
		$config['allowed_types'] = "xls|xlsx|xlsm|xlt|xltx|xltm";
		$config['file_name'] = $filename;
		$this->load->library('upload');
		$this->upload->initialize($config);
		if ($this->upload->do_upload($file)) {
			return $uploadFile;
		} else {
			$error = $this->upload->display_errors();
			echo $error;
		}
		exit();
	}
     
    private function create_guid_section($characters){
        $return = "";
        for($i=0; $i<$characters; $i++)
        {
            $return .= dechex(mt_rand(0,15));
        }
        return $return;
    }

    function redirect($url){
        header("Location:".$url);
		exit;
    }

	function uploadImg(){
		foreach ($_FILES as $inputName => $item){}
		if(empty($inputName)){
			output_response(-3,[],'没上传任何图片');
		}

		$img = $this->upload($inputName);
		if(is_array($img)) {
			output_response(-1,[],$img['msg']);
		}
		output_response(0,['imgPath'=>$img]);
	}

	public function upload($file)
	{
		$file = urldecode($file);
		if (empty($_FILES)) {
			show_404();
		}
		$name = $_FILES[$file]['name'];
		//print_r($_FILES[$file]);
		$arr = explode(".", $name);
		$n = count($arr) - 1;
		$extend = $arr[$n];

		//$fn = (isset($_SERVER['HTTP_X_FILENAME']) ? $_SERVER['HTTP_X_FILENAME'] : false);
		$docPath = $_SERVER['DOCUMENT_ROOT'];
		$dir = '/img/upload/';
		$dir = $dir.date("Ymd");
		$filename = date("dHis") . rand(1000, 9999) . '.' . $extend;
		$uploadFile = $dir .'/'. $filename;
		$this->_createDir($uploadFile);
		$fullPath = $docPath.$dir;

		$config['upload_path'] = $fullPath;
		$config['allowed_types'] = "*";
		$config['file_name'] = $filename;
		//$config['max_size'] = '20';

		$this->load->library('upload', $config);
		if (!$this->upload->do_upload($file)) {
			$error = $this->upload->display_errors();
			return ['errcode'=>0,'msg'=>$error];
		} else {
			return $uploadFile;
		}

	}
	function _createDir($file){
		$paths = explode("/",$file);
		$dir = $_SERVER['DOCUMENT_ROOT'];
		$last = count($paths) - 1;
		unset($paths[$last]);
		foreach ($paths as $path){
			if(!empty($path)){
				$dir = $dir.'/'.$path;
				if (!is_dir($dir)){
					mkdir($dir, 0777, true);
				}
			}

		}
	}

}