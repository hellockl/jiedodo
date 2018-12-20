<?php
class AZ_Model extends CI_Model {

    public $_table = '';
	function __construct()
	{
		parent::__construct();
        $this->load->database();
	}
    public function queryAll($where=[], $like=array(), $filed='', $limit=0, $start=0,$order = 'id desc')
    {
        if(is_array($like)&& !empty($like)) {
            foreach ($like as $k=>$v) {
                if($k==0) {
                    $this->db->like($k,$v);
                }else {
                    $this->db->or_like($k,$v);
                }

            }
        }

        if(!empty($where)){
            $this->db->where($where);
        }
        if($limit){
            return $this->db->select($filed)->from($this->_table)->order_by($order)->limit($limit,$start)->get()->result();
        }

        return $this->db->select($filed)->from($this->_table)->order_by($order)->get()->result();
    }
    function queryCount($where='', $like=''){
        if($where) {
            $this->db->where($where);
        }
        if($like!=''){
            $this->db->like($like);
        }
        return $this->db->from($this->_table)->count_all_results();
    }

    public function queryOne($where, $like=array(), $filed='')
    {
        if(is_array($like)&& !empty($like)) {
            foreach ($like as $k=>$v) {
                if($k==0) {
                    $this->db->like($k,$v);
                }else {
                    $this->db->or_like($k,$v);
                }
            }
        }
        return $this->db->select($filed)->from($this->_table)->where($where)->get()->row();
    }

    public function updates($where, $update)
    {
        return $this->db->where($where)->update($this->_table, $update);
    }

    //获取总数
    public function getCount($where, $like=array())
    {
        if(is_array($like)&& !empty($like)) {
            foreach ($like as $k=>$v) {
                if($k==0) {
                    $this->db->like($k,$v);
                }else {
                    $this->db->or_like($k,$v);
                }
            }
        }
        if(!empty($where)){
            $this->db->where($where);
        }

        return $this->db->from($this->_table)->where($where)->count_all_results();
    }

    public function inserts($data)
    {
        $result = $this->db->insert($this->_table, $data);
        if (empty($result)) {
            return false;
        } else {
            return $this->db->insert_id();
        }
    }
    public function insert_batchs($data)
    {
        return $this->db->insert_batch($this->_table, $data);
    }

    public function deletes($where)
    {
        if(empty($where)){
            return false;
        }
        return $result = $this->db->where($where)->delete($this->_table);
    }

    //批量删除（前边有where条件慎用）
    public function deletesAll($name,$where)
    {
        return $this->db->where_in($name,$where)->delete($this->_table);
    }
    
}
