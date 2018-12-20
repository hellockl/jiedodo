<?php
include_once 'TencentAI/include.php';
class CI_TencentAI
{
    public $config;
    public $manageHost = MANAGEHOST;
    public $officialAccountAppId;

    function __construct($config)
    {
        Configer::setAppInfo($config['appId'], $config['appKey']);
    }

    //人脸融合

    // ptu_facemerge ：人脸融合接口
    // 参数说明
    //   - $params：model-默认素材模板编码；
    //              image-待处理图片；
    // 返回数据
    //   - $response: ret-返回码；msg-返回信息；data-返回数据（调用成功时返回）；http_code-Http状态码（Http请求失败时返回）
    public function ptu_facemerge($params)
    {
        if(!isset($params['image'])) {
            return array('ret'=>1,'msg'=>'请选择一张图片');
        }
        $data   = file_get_contents('http://weixin.huiweibao.cn/'.$params['image']);
        $base64 = base64_encode($data);
        $params['image'] = $base64;
        if(!isset($params['model'])) {
            return array('ret'=>1,'msg'=>'请选择模板');
        }
        $response = API::ptu_facemerge($params);
        return $response;
    }


}