<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zou yong
 */
class Admin_model extends AZ_Model{

	public $_table = 'admin';

    public function __construct()
    {
        parent::__construct();
    }


    function config()
    {
        $result = $this->db->from('config')->get()->row();
        return $result;
    }
    function configUpdate($data){
        return $this->db->where('id',1)->update('config',$data);
    }


    //登陆
    function getAdminInfo($username,$pwd) {
        $admin=$this->db->select('username')->from($this->_table)->where('username', $username)->get()->row();
        if(!$admin || $admin->username!=$username){
            return false;
        }
        return $this->db->select()->from($this->_table)->where('username', $username)->where('password', $pwd)->get()->row();
    }

}