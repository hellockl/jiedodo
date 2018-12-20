<?php
class CI_Redisservice{
    public $CI;
    public $redis;
    public function __construct()
    {
        $this->CI = & get_instance();
        $this->CI->load->driver('cache', array('adapter' => 'redis'));
        echo $this->_adapter;
        $this->redis = $this->CI->cache->get_redis();
        var_dump($this->redis);exit;
        $this->cache = $this->CI->cache;
    }
}