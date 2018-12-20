<?php
class Type_model extends AZ_Model
{
    public $_table = 'type';

    public function __construct()
    {
        parent::__construct();
    }
    function details($id){
        $type=$this->db->from('type')->select('id,title,pid')->where('id',$id)->get()->row();
        $type->list=$this->db->from('type')->select('id,title,pid')->where('pid',$type->id)->get()->result();
        return $type;
    }
}