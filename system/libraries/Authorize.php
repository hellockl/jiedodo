<?php

class CI_Authorize
{
    private $ci;
    public $config;
    public $authorization_code;
    public $appId;
    public $officialAccountAppId;

    function __construct()
    {
        $this->ci = &get_instance();
        $this->ci->load->library('session');
        $this->ci->load->library('extend');
        $this->ci->load->driver('cache');
        $oauthConfig = isset($this->ci->config->config[OAUTH]) ? $this->ci->config->config[OAUTH] : die('系统参数未配置!');
        $config['token'] = $oauthConfig['token'];
        $config['appId'] = $oauthConfig['appId'];
        $config['appSecret'] = $oauthConfig['appSecret'];
        $config['encodingAesKey'] = $oauthConfig['encodingAesKey'];
        $config['componentTokenFileName'] = 'cmpToken'.$config['appId'];
        $config['ticketFileName'] = 'ticket_'.$config['appId'];
        $config['preCodeFileName'] = 'pre_auth_code_'.$config['appId'];
        $config['tokenFileName'] = 'new_token_';
        $this->config = $config;
    }

    /**************授权流程*************************/
    /**
     * 接收微信返回的ticket
     */
    public function getTicket()
    {
        $data = $this->xanalysis($this->config['token'], $this->config['encodingAesKey'], $this->config['appId']);
        if (empty($data)) {
            return false;
        }
        $this->error('info========='.var_export($data,true),'error_20171114.txt');
        switch ($data ['InfoType']) {
            case 'component_verify_ticket':// 授权凭证
                $component_verify_ticket = $data ['ComponentVerifyTicket'];
                //将ticket 存储到缓存中
                $this->ci->cache->redis->save($this->config['ticketFileName'], $component_verify_ticket,1800);
                $this->error('ticketValue==========='.$component_verify_ticket);
                break;
            case 'unauthorized' : // 取消授权
                //修改数据库状态
                $this->ci->load->model('home_model','home');
                $officialAccountAppid = $data['AuthorizerAppid'];
                $this->ci->home->unAuthorize($officialAccountAppid);
                break;
            case 'authorized' : // 授权
                //获取授权码
                $status = 1;
                break;
            case 'updateauthorized' : // 更新授权

                break;
        }
        return true;
    }

    /**
     * 授权解密函数
     * @param $token
     * @param $encodingAesKey
     * @param $appId
     * @return array|mixed
     */
    public function xanalysis($token, $encodingAesKey, $appId)
    {
        //$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];//取post数据
        $postStr = file_get_contents("php://input")?file_get_contents("php://input"):$GLOBALS["HTTP_RAW_POST_DATA"];
        $this->error('xanalysis_postStr==='.$postStr);
        $encrypt = $this->xmlToArray($postStr);
        $this->error('xanalysis_encrypt==='.json_encode($encrypt));
        $format = "<xml><ToUserName><![CDATA[toUser]]></ToUserName><Encrypt><![CDATA[%s]]></Encrypt></xml>";
        $formatXml = sprintf($format, $encrypt['Encrypt']);
        $this->error('xanalysis_formatXml==='.$formatXml);
        $formatXml = $this->msgCrypt($formatXml, $token, $encodingAesKey, $appId, 'decode');
        $this->error('xanalysis_decode_formatXml==='.$formatXml);
        if (!empty($postStr)) {
            $data = $this->xmlToArray($formatXml);
        } else {
            $data = array();
        }
        $this->error('xanalysis_return_data==='.json_encode($data));
        return $data;
    }

    /**
     * 获取component_access_token
     */

    public function getComponentAccessToken()
    {
        //获取缓存中是否有值
        $fileName = $this->config['componentTokenFileName'];
        $componentAccessToken = $this->isExpired($fileName);
        if (!$componentAccessToken) {
            $post_json = array();
            $url = 'https://api.weixin.qq.com/cgi-bin/component/api_component_token';
            $post_json['component_appsecret'] = $this->config['appSecret'];
            $post_json['component_verify_ticket'] = $this->ci->cache->redis->get($this->config['ticketFileName']);
            if(empty($post_json['component_verify_ticket'])){
                exit('系统初始化中，请稍后再试');
            }
            $post_json['component_appid'] = $this->config['appId'];
            $data = json_encode($post_json);
            $json = $this->ci->extend->getRemoteDataJson($url, $data);
            $this->error('获取componentToken:'.$json,'getComponentToken'.Date('Ymd'));
            /* 返回状态 数据
             * {
                "component_access_token":"61W3mEpU66027wgNZ_MhGHNQDHnFATkDa9-2llqrMBjUwxRSNPbVsMmyD-yq8wZETSoE5NQgecigDrSHkPtIYA",
                "expires_in":7200
              }*/
            $json = json_decode($json, true);
            if(isset($json['component_access_token'])){
                $componentAccessToken = $json['component_access_token'];
                $expiresTime = $json['expires_in'] - 600;
                $res = $this->ci->cache->redis->save($fileName, $componentAccessToken, $expiresTime);
                if ($res == false) {
                    $this->saveAccessToken($fileName, $componentAccessToken, $expiresTime);
                }
            }else {
                $this->error('component_access_token====='.json_encode($json));
                if(isset($json['errcode'])){
                    exit($json['errcode']."　　　　".$json['errmsg']);
                }else{
                    exit('获取Component AccessToken 时遇到异常!请联系系统管理员处理...');
                }
                return false;
            }
        }
        return $componentAccessToken;
    }

    /**
     * 获取预授权码
     */
    public function getPreAuthCode()
    {
        $fileName = $this->config['preCodeFileName'];
        $preAuthCode = $this->isExpired($fileName);
        //if (!$preAuthCode) {
            $post_json = array();
            $url = 'https://api.weixin.qq.com/cgi-bin/component/api_create_preauthcode?component_access_token=';
            $componentAccessToken = $this->getComponentAccessToken();
            $url .= $componentAccessToken;
            $post_json['component_appid'] = $this->config['appId'];
            $data = json_encode($post_json);
            $json = $this->ci->extend->getRemoteDataJson($url, $data);
            /*{
                "pre_auth_code":"Cx_Dk6qiBE0Dmx4EmlT3oRfArPvwSQ-oa3NL_fwHM7VI08r52wazoZX2Rhpz1dEw",
                "expires_in":600
            }*/
            $json = json_decode($json, true);
            if (isset($json['pre_auth_code']) && isset($json['expires_in'])){
                $preAuthCode = $json['pre_auth_code'];
                $expiresTime = $json['expires_in'];
                $res = $this->ci->cache->redis->save($fileName, $preAuthCode, $expiresTime);
                if ($res == false) {
                    $this->saveAccessToken($fileName, $preAuthCode, $expiresTime);
                }
            } else {
                if(isset($json['errcode'])){
                    exit($json['errcode']."　　　　".$json['errmsg']);
                }else{
                    exit('获取预授权码时遇到异常!请联系系统管理员处理...');
                }
            }
        //}
        return $preAuthCode;
    }

    /**
     * 清除授权码
     */
    public function clearPreCode()
    {
        $fileName = $this->config['preCodeFileName'];
        $this->ci->cache->redis->save($fileName, '', 1);
        if (file_exists(rtrim($_SERVER['DOCUMENT_ROOT'], 'public') . "cache/accesstoken/" . $fileName . ".txt")) {
            file_put_contents(rtrim($_SERVER['DOCUMENT_ROOT'],'public')."cache/accesstoken/".$fileName.".txt",json_encode(array(0,0)));
        }
    }
    /**
     * 存储refresh_access_token
     */
    public function saveRefreshAccessToken($authCode)
    {
        if (empty($authCode)) {
            exit('未获取到authCode');
        }
        $post_json = array();
        $url = 'https://api.weixin.qq.com/cgi-bin/component/api_query_auth?component_access_token=';
        $componentAccessToken = $this->getComponentAccessToken();
        $url .= $componentAccessToken;
        $post_json['component_appid'] = $this->config['appId'];
        $post_json['authorization_code'] = $authCode;
        $data = json_encode($post_json);
        $json = $this->ci->extend->getRemoteDataJson($url, $data);
        /*
        {
            "authorization_info": {
                "authorizer_appid": "wxf8b4f85f3a794e77",
                "authorizer_access_token": "QXjUqNqfYVH0yBE1iI_7vuN_9gQbpjfK7hYwJ3P7xOa88a89-Aga5x1NMYJyB8G2yKt1KCl0nPC3W9GJzw0Zzq_dBxc8pxIGUNi_bFes0qM",
                "expires_in": 7200,
                "authorizer_refresh_token": "dTo-YCXPL4llX-u1W1pPpnp8Hgm4wpJtlR6iV0doKdY",
                "func_info": [
                    {
                        "funcscope_category": {"id": 1}
                    },
                    {
                        "funcscope_category": {"id": 2}
                    },
                    {
                        "funcscope_category": {"id": 3}

                    }
                ]
            }
        }
        */
        $json = json_decode($json, true);
        if (!isset($json['authorization_info'])){
            if(isset($json['errcode'])){
                exit($json['errcode']."　　　　".$json['errmsg']);
            }else{
                exit('获取Refresh AccessToken 时遇到异常!请联系系统管理员处理...');
            }
        }
        $json = $json['authorization_info'];
        $this->error('json======'.var_export($json,true));
        $accessToken = $json['authorizer_access_token'];
        $expiresTime = $json['expires_in'];
        $fileNames = $this->config['tokenFileName'] . $json['authorizer_appid'];
        $res = $this->ci->cache->redis->save($fileNames, $accessToken, $expiresTime - 600);
        if ($res == false) {
            $this->saveAccessToken($fileNames, $accessToken, $expiresTime - 600);
        }
        return $json;
    }

    public function getOauthorizeInfo($officialAccountAppId){
        $post_json = array();
        $url = 'https://api.weixin.qq.com/cgi-bin/component/api_get_authorizer_info?component_access_token=';
        $componentAccessToken = $this->getComponentAccessToken();
        $url .= $componentAccessToken;
        $post_json['component_appid'] = $this->config['appId'];
        $post_json['authorizer_appid'] = $officialAccountAppId;
        $data = json_encode($post_json);
        $json = $this->ci->extend->getRemoteDataJson($url, $data);
        $json = json_decode($json, true);
        /*
         * {
            "authorizer_info": {
                "nick_name": "微信SDK Demo Special",
                "head_img": "http://wx.qlogo.cn/mmopen/GPy",
                "service_type_info": { "id": 2 },
                "verify_type_info": { "id": 0 },
                "user_name":"gh_eb5e3a772040",
                "principal_name":"腾讯计算机系统有限公司",
                "business_info": {"open_store": 0, "open_scan": 0, "open_pay": 0, "open_card": 0, "open_shake": 0},
                "alias":"paytest01"
                "qrcode_url":"URL",
                },
                "authorization_info": {
                "appid": "wxf8b4f85f3a794e77",
                "func_info": [
                { "funcscope_category": { "id": 1 } },
                { "funcscope_category": { "id": 2 } },
                { "funcscope_category": { "id": 3 } }
                ]
              }
           }*/
        if(!isset($json['authorizer_info'])){
            $this->error('userInfo======'.var_export($json,true));
            if(isset($json['errcode'])){
                exit($json['errcode']."　　　　".$json['errmsg']);
            }else{
                exit('获取用户信息时遇到异常!');
            }
            return false;
        }
        $json = $json['authorizer_info'];
        return $json;
    }

    /*public function getNewOauthAccessToken($realTime = '')
    {
        $access_token = $this->isExpired($this->config['accessTokenFileName']);
        if (!$access_token || $realTime) {
            $post_json = array();
            $url = 'https://api.weixin.qq.com/cgi-bin/component/api_authorizer_token?component_access_token=';
            $componentAccessToken = $this->getComponentAccessToken();
            $url .= $componentAccessToken;
            $post_json['component_appid'] = $this->config['appId'];
            $post_json['authorizer_appid'] = $this->config['authAppId'];
            $post_json['authorizer_refresh_token'] = $this->getRefreshAccessToken();
            $data['post_json'] = json_encode($post_json);
            $json = $this->ci->extend->getRemoteDataJson($url, $post_json);
            $json = json_decode($json, true);
            $accessToken = $json['authorizer_access_token'];
            $accessRefreshAccessToken = $json['authorizer_refresh_token'];
            $expiresTime = time() + $json['expires_in'];
            $res = $this->ci->cache->memcached->save($this->config['accessTokenFileName'], $accessToken, $expiresTime - 600);
            //数据库存储access_refresh_token
            $this->ci->load->model('home_model','home');
            $this->ci->home->saveRefreshAccessToken($this->officialAccountAppId,$accessRefreshAccessToken);
            if ($res == false) {
                $this->saveAccessToken($this->config['accessTokenFileName'], $accessToken, $expiresTime - 600);
            }
        }
        return $access_token;
    }*/

    //判断是否过期
    function isExpired($fileName)
    {
        $host = $_SERVER['HTTP_HOST'];
        if ($host == '127.0.0.1' || $host == '::1' || $host == 'localhost' || strpos($host, '192.168.') !== false || strpos($host, 'anzeen.net') !== false || strpos($host, 'anzeen.cn') !== false) {
            exit("本地不允许拿: subscribeAccessToken");
        }
        $access_token = $this->ci->cache->redis->get($fileName);
        $this->error('access_token_redis======'.$access_token);
        if ($access_token == '') {
            if (file_exists(rtrim($_SERVER['DOCUMENT_ROOT'], 'public') . "cache/accesstoken/" . $fileName . ".txt")) {
                $data = json_decode(file_get_contents(rtrim($_SERVER['DOCUMENT_ROOT'], 'public') . "cache/accesstoken/" . $fileName . ".txt"));
                if ($data[1] < time()) {
                    $access_token = '';
                } else {
                    $access_token = $data[0];
                }
            }
        }

        return $access_token;
    }

    /**
     * 消息加解密
     * @param $xml
     * @param string $type
     * @param $token
     * @param $encodingAesKey
     * @param $appId
     * @return mixed
     */
    private function msgCrypt($xml,$token,$encodingAesKey,$appId,$type='encode')
    {
        $encrypt_type = $this->ci->input->get('encrypt_type');
        $error_code = '';
        if($encrypt_type=='aes')
        {
            $timestamp      = $this->ci->input->get('timestamp');
            $nonce          = $this->ci->input->get('nonce');
            $msg_signature  = $this->ci->input->get('msg_signature');
            include_once 'Weixin/wxBizMsgCrypt.php';
            $pc = new WXBizMsgCrypt($token, $encodingAesKey, $appId);

            if($type == 'encode'){
                $error_code = $pc->encryptMsg($xml, $timestamp, $nonce, $xml);
            }else{
                $error_code = $pc->DecryptMsg($msg_signature, $timestamp, $nonce, $xml, $xml);
            }
        }
        if($error_code!=0){
            $this->error('msgCrypt++'.$type.'++'.$error_code.'============='.$xml);
        }
        return $xml;
    }

    //将XML转为array
    public function xmlToArray($xml)
    {
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $values;
    }

    function error($msg,$filename = ''){
        $errors = debug_backtrace();
        if(empty($filename)) $filename = 'error.txt';
        file_put_contents(APPPATH."logs/".$filename,'------ '.date("Y-m-d H:i:s")."\n",FILE_APPEND);
        file_put_contents(APPPATH."logs/".$filename,$errors[0]['file']."\n".$errors[1]['function']."\n"."第".$errors[0]['line']."行\n".$errors[0]['class']."\n".__FUNCTION__."\n第".__LINE__."行\n",FILE_APPEND);
        file_put_contents(APPPATH."logs/".$filename,$msg."\n\n",FILE_APPEND);
    }

    function getRemoteDataJson($url,$data)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $curl, CURLOPT_SSL_VERIFYHOST, false );
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($curl);
        //var_dump($result);die;
        curl_close($curl);//关闭
        return $result;
    }

    function saveAccessToken($tokenFileName,$access_token,$expiresTime){
        $data[] = $access_token;
        $data[] = $expiresTime + 600;
        file_put_contents(rtrim($_SERVER['DOCUMENT_ROOT'],'public')."cache/accesstoken/".$tokenFileName.".txt",json_encode($data));
    }
}