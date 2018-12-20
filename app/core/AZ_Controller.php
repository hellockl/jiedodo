<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class AZ_Controller extends CI_Controller{

    public $cdn='';
    function __construct(){
        parent::__construct();
        $this->cdn = !empty($this->cdn) ? $this->cdn : 'http://' . $_SERVER['HTTP_HOST'];
    }
    function upload()
    {
        if (empty($_FILES)) {
            output_response(1,[],'请选择图片');
            return ['code'=>1,'msg'=>'请选择图片'];
        }
        foreach ($_FILES as $file => $item){}
        $file = urldecode($file);
        $name = $_FILES[$file]['name'];
        $arr = explode(".", $name);
        $n = count($arr) - 1;
        $extend = $arr[$n];

        $docPath = $_SERVER['DOCUMENT_ROOT'];
        $dir = '/img/';
        $dir = $dir.date("Ymd");
        $filename = date("dHis") . rand(1000, 9999) . '.' . $extend;
        $uploadFile = $dir .'/'. $filename;
        $this->_createDir($uploadFile);
        $fullPath = $docPath.$dir;

        $config['upload_path'] = $fullPath;
        $config['allowed_types'] = "*";
        $config['file_name'] = $filename;
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload($file)) {
            $error = $this->upload->display_errors();
            output_response(1,[],$error);
            return ['code'=>1,'msg'=>$error];
        } else {
            output_response(0,['url'=>$uploadFile]);
            return ['code'=>0,'msg'=>$uploadFile];
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

	/**
	 * 发送短信
	 *
	 * @param string $mobile 		手机号码
	 * @param string $msg 			短信内容
	 * @param string $needstatus 	是否需要状态报告
	 */
	public function sendSMS($mobile, $msg, $needstatus = 'true') {
		$url='http://smssh1.253.com/msg/send/json';
		$account='N2754133';
		$password='e3rpRZuvDab4e8';
		//创蓝接口参数
		$postArr = array (
			'account'  => $account,
			'password' => $password,
			'msg' => urlencode($msg),
			'phone' => $mobile,
			'report' => $needstatus
        );
		$result = $this->smscurlPost($url, $postArr);
		//var_dump($postArr);die();
		return $result;
	}
	/**
	 * 通过CURL发送HTTP请求
	 * @param string $url  //请求URL
	 * @param array $postFields //请求参数 
	 * @return mixed
	 *  
	 */
	private function smscurlPost($url,$postFields){
		$postFields = json_encode($postFields);
		
		$ch = curl_init ();
		curl_setopt( $ch, CURLOPT_URL, $url ); 
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json; charset=utf-8'   //json版本需要填写  Content-Type: application/json;
			)
		);
		curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4); 
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_POST, 1 );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt( $ch, CURLOPT_TIMEOUT,60); 
        curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0);
		$ret = curl_exec ( $ch );
        if (false == $ret) {
            $result = curl_error($ch);
        } else {
            $rsp = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if (200 != $rsp) {
                $result = "请求状态 ". $rsp . " " . curl_error($ch);
            } else {
                $result = $ret;
            }
        }
		curl_close ( $ch );
		return $result;
	}
}