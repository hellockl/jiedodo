<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Application extends CI_Controller{
	
	protected $_uid;
    protected $limit = 10;

    /**
     * 构造函数
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        //$this->load->model('User_model','user');
        $this->load->helper('url');
        $this->load->config('errcode');
        $this->_auth();
    }


    /**
     * 验证用户是否登录
     *
     * @author gengzhiguo@xiongmaojinfu.com
     * @return bool
     */
    protected function _auth()
    {

        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        } else {
            //获取用户id
            $userInfo = $this->session->get_userdata();
            $userId = $userInfo['user']->id;
            $this->_uid = $userId;
            //获取角色id
            // $roleId = $this->u_user_role->getRoleId($userId);
            // //通过角色ID获取该有的权限
            // $permission = $this->u_user_role->getRolePermission($roleId);
            // if (!empty($permission)) {
            //     $this->twig->getTwig()->addGlobal('menu', $permission);
            // } else {
            //     session_destroy();
            //     redirect('login');
            //     return FALSE;
            // }
        }    
	}
	
	function get_err_msg($code)
	{
		return $this->config->item($code, 'errcode');
	}


    public function upload($time, $type)
    {
        $begenpath = 'public/uploads/';
        if (!file_exists($begenpath)) {
            mkdir($begenpath);
        }
        $config['upload_path'] = 'public/uploads/' . $time . '/';
        $path = $config['upload_path'];
        if (!file_exists($path)) {
            mkdir($path);
        }
        $config['allowed_types'] = 'gif|jpg|png|jpeg|bmp';
        $config['file_name'] = time() . '_' . rand(0, 99);
        $this->load->library('upload', $config);
        $data = '';
        if (!$this->upload->do_upload()) {
            if ($type == 'create') {
//                $image_message = array('error' => $this->upload->display_errors());
//                $error = $image_message['error'];
//                $data = $error;
            } elseif ($type == 'edit') {
                $data['is_image'] = '0';//更新时状态0说明没有提交新图片，不需要更新
            }
        } else {//成功后生成缩略图
            $data = array('upload_data' => $this->upload->data());
            $orginal_image = $data['upload_data']['full_path'];
            $o_images_path = explode('.', $orginal_image);
            $orginal_images = base_url() . 'public/uploads/' . $time . '/' . $config['file_name'] . '.' . $o_images_path[1];
            $config['image_library'] = 'gd2';
            $config['source_image'] = $orginal_image;
            $config['create_thumb'] = TRUE;
            $config['maintain_ratio'] = TRUE;
            $config['width'] = 75;
            $config['height'] = 50;
            $this->load->library('image_lib', $config);
            $this->image_lib->resize();
            //缩略图全路径名称
            $thumb_images = base_url() . 'public/uploads/' . $time . '/' . $config['file_name'] . '_thumb.' . $o_images_path[1];
            $data['thumb_images'] = $thumb_images;
            $data['orginal_images'] = $orginal_images;
            $data['is_image'] = '1';
        }

        return $data;
    }
}
