<?php
class News_model extends AZ_Model
{
    public $_table = 'news';

    public function __construct()
    {
        parent::__construct();
    }
    function userAll($where, $like, $limit, $start){
        if($where) {
            $this->db->where($where);
        }
        if($like!=''){
            $this->db->like($like);
        }
        $data = $this->db->from($this->_table)->limit($limit, $start)->order_by('hot desc')->order_by('id desc')->get()->result();
        return $data;
    }
    function userCount($where, $like){
        if($where) {
            $this->db->where($where);
        }
        if($like!=''){
            $this->db->like($like);
        }
       return $this->db->from($this->_table)->count_all_results();
    }
    function newsAdd($post){
        $post['jump']=$post['url']!=''?1:0;
        if($post['id']==''){
            unset($post['id']);
            if($post['title']=='' || $post['image']=='' || $post['content']==''){
                return '都不能为空';
            }
            if(!isset($post['hot'])){
                $post['hot']=0;
            }
            return  $this->db->insert('news',$post);
        }else{
            if($post['id']=='' || $post['title']=='' || $post['content']==''){
                return '都不能为空';
            }
            if($post['image']==''){
                unset($post['image']);
            }
            if(!isset($post['hot'])){
                $post['hot']=0;
            }
            return  $this->db->where('id',$post['id'])->update('news',$post);
        }
    }
}