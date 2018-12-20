<?php

class CI_Fwhweixin {
    private $ci;
    public $config;
    private $pubId;
    public $manageHost = MANAGEHOST;
    public $officialAccountAppId;
    function __construct()
    {
        $this->ci = &get_instance();
        $this->ci->load->library('session');
        $this->ci->load->library('extend');
        $this->ci->load->driver('cache');
        $this->ci->load->model('home_model','home');
     //   $officialAccount = $this->ci->bonusOwnerOfficialAccount;
      //  $oauthConfig = isset($this->ci->config->config[OAUTH]) ? $this->ci->config->config[OAUTH] : die('系统参数未配置!');

        $officialAccount = $this->ci->officialAccount;
        $oauthConfig = $this->ci->config->item('weixin');

        if($officialAccount->isAuthorize == 1 ){
            $token = $oauthConfig['token'];
            $appId = $oauthConfig['appId'];
            $appSecret = $oauthConfig['appSecret'];
            $encodingAesKey = $oauthConfig['encodingAesKey'];
            $config['componentTokenFileName'] = 'cmpToken'.$appId;
            $config['officialAccountAppId'] = $officialAccount->appId;
            $config['ticketFileName'] = 'ticket_'.$appId;
            $config['preCodeFileName'] = 'pre_auth_code_'.$appId;
            $this->officialAccountAppId = $officialAccount->appId;
            //$config['tokenFileName'] = 'token_'.$officialAccount->appId;
            $config['tokenFileName'] = 'new_token_'.$officialAccount->appId;  // access_token fileName
            $config['jsTicketFileName'] = 'new_ticket_'.$officialAccount->appId;

        }else{
            $token = $officialAccount->token;
            $appId = $officialAccount->appId;
            $appSecret = $officialAccount->appSecret;
            $encodingAesKey = $officialAccount->encodingAesKey;
            $config['tokenFileName'] = 'token_'.$officialAccount->appId;  // access_token fileName
            $config['jsTicketFileName'] = 'ticket_'.$officialAccount->appId;
        }
        $config['appId'] = $appId;
        $config['appSecret'] = $appSecret;
        $config['token'] = $token;
        $config['encodingAesKey'] = $encodingAesKey;
        $config['timestamp'] = strtotime(date('Y-m-d'));
        $config['noncestr'] = '79345058@qq.com';
        $config['redirect'] = 'http://'. $_SERVER['HTTP_HOST'] .'/';
        //$config['officialAccountToken'] = $officialAccount->token;
        $config['mchId'] = $officialAccount->mchId;
        $config['payKey'] = $officialAccount->payKey;
        if(strripos(FCPATH,"manage")){
            $config['apiClientCert'] =  substr(FCPATH,0,strripos(FCPATH,"manage")-1).$officialAccount->apiClientCert;
            $config['apiClientKey'] = substr(FCPATH,0,strripos(FCPATH,"manage")-1).$officialAccount->apiClientKey;
            $config['rootCa'] =  substr(FCPATH,0,strripos(FCPATH,"manage")-1).$officialAccount->rootCa;
        }else {
            $config['apiClientCert'] = $officialAccount->apiClientCert;
            $config['apiClientKey'] = $officialAccount->apiClientKey;
            $config['rootCa'] = $officialAccount->rootCa;
        }
        $config['notifyUrl'] = 'http://'.$_SERVER['HTTP_HOST'].'/notify';
        $config['noncestr'] = '79345058@qq.com';
        $this->pubId = $officialAccount->appId;
        $this->config = $config;
        //exit;
    }

    /***********新添加的授权方法***************/
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
        $postStr = file_get_contents("php://input");
        $this->error('xanalysis_postStr==='.$postStr);
        //file_put_contents('ztlogs1.txt',$postStr);
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
        if(!$componentAccessToken){
            $post_json = array();
            $url = 'https://api.weixin.qq.com/cgi-bin/component/api_component_token';
            $post_json['component_appsecret'] = $this->config['appSecret'];
            $post_json['component_verify_ticket'] = $this->ci->cache->redis->get($this->config['ticketFileName']);
            $post_json['component_appid'] = $this->config['appId'];
            $data = json_encode($post_json);
            $json = $this->ci->extend->getRemoteDataJson($url,$data);
            $json = json_decode($json,true);
            $componentAccessToken   = $json['component_access_token'];
            $expiresTime    = $json['expires_in'] - 600;
            $res = $this->ci->cache->redis->save($fileName, $componentAccessToken, $expiresTime);
            if($res == false){
                $this->saveAccessToken($fileName,$componentAccessToken ,$expiresTime);
            }
        }
        return $componentAccessToken;
    }

    /**
     * 获取refresh_access_token
     */
    public function getRefreshAccessToken()
    {
        //$this->ci->load->model('home_model','home');
        $refreshAccessToken = $this->ci->home->getRefreshAccessToken($this->officialAccountAppId);
        return $refreshAccessToken;
    }

    public function saveRefreshAccessToken($refreshAccessToken){
        $data = $this->ci->home->saveRefreshAccessToken($this->officialAccountAppId,$refreshAccessToken);
    }
    /**
     * 新方法获取access_token
     * @param string $realTime
     * @return string
     */
    public function getNewOauthAccessToken($realTime='')
    {
        $access_token = $this->isExpired($this->config['tokenFileName']);
        if(!$access_token || $realTime){
            $post_json = array();
            $url = 'https://api.weixin.qq.com/cgi-bin/component/api_authorizer_token?component_access_token=';
            $componentAccessToken = $this->getComponentAccessToken();
            $url.=$componentAccessToken;
            $post_json['component_appid'] = $this->config['appId'];
            $post_json['authorizer_appid'] = $this->officialAccountAppId;
            $post_json['authorizer_refresh_token'] = $this->getRefreshAccessToken();
            $data = json_encode($post_json);
            $json = $this->ci->extend->getRemoteDataJson($url,$data);
            $json = json_decode($json,true);
            $accessToken   = $json['authorizer_access_token'];
            $accessRefreshAccessToken = $json['authorizer_refresh_token'];
            $this->saveRefreshAccessToken($accessRefreshAccessToken);
            $expiresTime   = $json['expires_in'];
            $res = $this->ci->cache->redis->save($this->config['tokenFileName'], $accessToken, $expiresTime-600);
            //数据库存储access_refresh_token
            if($res == false){
                $this->saveAccessToken($this->config['tokenFileName'],$accessToken ,$expiresTime-600);
            }
        }
        return $access_token;
    }

    //判断是否过期
    function isExpired($fileName){
        $host = $_SERVER['HTTP_HOST'];
        if($host=='127.0.0.1' || $host=='::1' || $host=='localhost' || strpos($host, '192.168.')!==false || strpos($host, 'anzeen.net')!==false || strpos($host, 'anzeen.cn')!==false)
        {
            exit("本地不允许拿: subscribeAccessToken");
        }
        $access_token = $this->ci->cache->redis->get($fileName);
        /*if($access_token == ''){
            if(file_exists(rtrim($_SERVER['DOCUMENT_ROOT'],'public')."cache/accesstoken/".$fileName.".txt")){
                $data = json_decode(file_get_contents(rtrim($_SERVER['DOCUMENT_ROOT'],'public')."cache/accesstoken/".$fileName.".txt"));
                if($data[1] < time()){
                    $access_token ='';
                }else{
                    $access_token = $data[0];
                }
            }
        }*/

        return $access_token;
    }

    /**
     * @param $token 验证微信授权，主要用于取微信openid
     * @param string $scope string $scope //snsapi_base：不弹授权页面，snsapi_userinfo：弹授权页面
     * @return bool
     */
    public function chkOAuth($token,$scope = 'snsapi_base')
    {
        if(!$this->ci->session->has_userdata('fwhWxInfo'.$this->pubId))
        {
            //要回跳的页面
            $host = 'http://'. $_SERVER['HTTP_HOST'];
            $uri = $_SERVER['REQUEST_URI'];

            //如果是本地，使用假的微信数据
            $this->_localTest($uri);
            //把地址里的?号换成:，&换成$，/换成~
            $uri = str_replace('?', ':', $uri);
            $uri = str_replace('&', '$', $uri);
            $uri = str_replace('/', '~', $uri);
            $redirect = urlencode($host.'/api/fwhOauth/'.$token.'/'.$uri);

            if($this->officialAccountAppId){
                $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='. $this->officialAccountAppId.'&redirect_uri='. $redirect .'&response_type=code&scope='. $scope .'&state=1&component_appid='.$this->config['appId'].'#wechat_redirect';
            }else{
                $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='. $this->config['appId'] .'&redirect_uri='. $redirect .'&response_type=code&scope='. $scope .'&state=1#wechat_redirect';
            }
            //echo $url;exit;
            $this->error('urlurl2========='.$url);
            header('location:'.$url);
            exit;
        }else{
            return true;
        }
    }

    /**
     * 本地测试
     * @param $url
     */
    private function _localTest($url)
    {
        $local = isset($_GET['local']) ? true : false;
        if($this->_isTestHost() || $local)
        {
            if(!$this->ci->session->has_userdata('cookieId') || !$this->ci->session->has_userdata('azNickName') || !$this->ci->session->has_userdata('azUnionId')){
                $cookieUrl = '/api/cookie?url='.urldecode($url);
                $this->ci->redirect($cookieUrl);
            }else{
                $json = '{
                "subscribe": 1,
                "openid": "'.$this->ci->session->userdata('cookieId').'",
                "nickname": "'.$this->ci->session->userdata('azNickName').'",
                "sex": 1,
                "language": "zh_CN",
                "city": "北京",
                "province": "通州",
                "country": "中国",
                "headimgurl":"http://cdn.weechao.com/img/az-vchat.png",
                "subscribe_time":18810392603,
                "unionid": "'.$this->ci->session->userdata('azUnionId').'"}';

                $weixinInfo = json_decode($json, true);
                $this->ci->session->set_userdata('fwhWxInfo'.$this->pubId, $weixinInfo);
                header('location:'.$url);
                exit;
            }
            //未关注
            $json = '{"subscribe": 0, "openid": "o6_bmjrPTlm6_2sgVt7hMZOPf13M"}';
            //已关注

        }
    }

    /**
     * 判断是不是测试服务器
     * @return bool
     */
    function _isTestHost(){
        return false;
        if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false || isset($_GET['signature'])) {
            return false;
        }
        return true;
        /*$ip = gethostbyname($_SERVER["HTTP_HOST"]);
        $servers[] = '115.28.228.70';
        $servers[] = '101.200.89.244';
        $servers[] = '123.57.20.30';
        $servers[] = '182.92.157.43';
        $servers[] = '210.77.144.36';
        $servers[] = '101.201.178.171';
        $servers[] = '123.56.236.51';

        if(in_array($ip,$servers)){
            return false;
        }else{
            return true;
        }*/
    }

    /**
     * 取微信openid
     */
    public function getOpenId()
    {
        
        $params = $this->ci->uri->segment(4,'');
        //把地址里的参数换回来：?号换成:，&换成$，/换成~
        $uri = str_replace(':', '?', $params);
        $uri = str_replace('$', '&', $uri);
        $uri = str_replace('~', '/', $uri);

        $host = 'http://'. $_SERVER['HTTP_HOST'];
        $redirect = $host . $uri;

        //取得授权信息
        $json   = $this->_getOauthAccessToken();
        if(empty($json['openid']))
        {
            //验证失败跳转回去重头来
            sleep(2);
        }
        header('location:'.$redirect);
    }

    /**
     * 重新取关注信息（有些页面需要即时判断用户是否关注）
     * @param bool $realTime
     * @param string $openId
     * @return mixed
     */
    function getSubscribeUserInfo($realTime=true,$openId = '')
    {
        if($this->_isTestHost()){
            $json = '{
            "subscribe": 1,
            "openid": "'.$this->ci->session->userdata('cookieId').'",
            "nickname": "'.$this->ci->session->userdata('azNickName').'",
            "sex": 1,
            "language": "zh_CN",
            "city": "北京",
            "province": "通州",
            "country": "中国",
            "headimgurl":"http://cdn.weechao.com/img/az-vchat.png",
            "subscribe_time":18810392603,
            "unionid": "'.$this->ci->session->userdata('azUnionId').'"
            }';
            $userInfo = json_decode($json, true);
            $this->ci->session->set_userdata('fwhWxInfo'.$this->pubId, $userInfo);
            return $userInfo;
        }
        $userInfo = $this->ci->session->userdata('fwhWxInfo'.$this->pubId);
        if($realTime || !isset($userInfo['subscribe']))
        {
            $openId = !empty($openId) ? $openId : $userInfo['openid'];

            $access_token = $this->_getSubscribeAccessToken();
            //var_dump($access_token);
            //取得用户信息
            $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=' . $access_token . '&openid=' . $openId . '&lang=zh_CN';
            //echo $url;exit;
            $json = $this->ci->extend->getRemoteData($url);
            $json = json_decode($json, true);

            //如果获取失败，再重新获取一次
            if(isset($json['errcode']) && $json['errcode']==40001 && strpos($json['errmsg'], 'access_token'))
            {
                $access_token = $this->_getSubscribeAccessToken(true);
                //取得用户信息
                $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=' . $access_token . '&openid=' . $openId . '&lang=zh_CN';
                $json = $this->ci->extend->getRemoteData($url);
                $json = json_decode($json, true);
            }

            /*
             * 错误时返回
             * {
             * "errcode":40013,
             * "errmsg":"invalid appid"
             * }
             * 未关注返回
             * {
             * "subscribe": 0,
             * "openid": "o6_bmjrPTlm6_2sgVt7hMZOPfL2M",
             * }
             * 关注后返回
             * {
             * "subscribe": 1,
             * "openid": "o6_bmjrPTlm6_2sgVt7hMZOPfL2M",
             * "nickname": "Band",
             * "sex": 1,
             * "language": "zh_CN",
             * "city": "广州",
             * "province": "广东",
             * "country": "中国",
             * "headimgurl":    "http://wx.qlogo.cn/mmopen/g3MonUZtNHkdmzicIlibx6iaFqAc56vxLSUfpb6n5WKSYVY0ChQKkiaJSgQ1dZuTOgvLLrhJbERQQ4eMsv84eavHiaiceqxibJxCfHe/0",
             * "subscribe_time": 1382694957,
             * "unionid": " o6_bmasdasdsad6_2sgVt7hMZOPfL"
             * }
             */
            if(isset($json['subscribe']))
            {
                if($json['subscribe'])
                    $userInfo = $json;
                else{
                    $userInfo['subscribe'] = 0;
                    $userInfo['subscribe_time'] = '';
                }
                $this->ci->session->set_userdata('fwhWxInfo'.$this->pubId, $userInfo);
            }
        }
        //print_r($userInfo);exit;
        return $userInfo;
    }

    /**
     * 取微信js api数据
     * @return array
     */
    public function getJsApiData()
    {

        $ticket = $this->_getJsTicket();

        $str = "jsapi_ticket={$ticket}&noncestr={$this->config['noncestr']}&timestamp={$this->config['timestamp']}&url=http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
        $signature  = sha1($str);

        if($this->officialAccountAppId){
            $appId = $this->officialAccountAppId;
        }else{
            $appId = $this->config['appId'];
        }

        $data = array(
            'appid'     => $appId,
            'noncestr'  => $this->config['noncestr'],
            'timestamp' => $this->config['timestamp'],
            'signature' => $signature
        );

        return $data;
    }

    /*
     * 获取网页授权时的用户信息
     */
    private function _getOauthAccessToken()
    {
        $code   = $this->ci->input->get('code');
        if($this->officialAccountAppId){
            $url = 'https://api.weixin.qq.com/sns/oauth2/component/access_token?appid='.$this->officialAccountAppId.'&code='.$code.'&grant_type=authorization_code&component_appid='.$this->config['appId'].'&component_access_token='.$this->getComponentAccessToken();

        }else {
            $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='. $this->config['appId'] .'&secret='. $this->config['appSecret'] .'&code='. $code .'&grant_type=authorization_code';

        }
        $json   = $this->ci->extend->getRemoteData($url);
        $json   = json_decode($json, true);
        //access_token/expires_in/refresh_token/openid/scope
        if(!empty($json['openid']))
        {
            $wxInfo['openid'] = $json['openid'];
            $this->ci->session->set_userdata('fwhWxInfo'.$this->pubId, $wxInfo);
            $this->_getWeixinInfo($json);
        }else {
            $this->error('getOauthAccessToken========='.var_export($json,true),'api_log.txt');
        }

        return $json;
    }

    /**
     * 网页授权时取用户微信信息
     * @param array $data
     */
    private function _getWeixinInfo($data)
    {
        $url = 'https://api.weixin.qq.com/sns/userinfo?access_token='. $data['access_token'] .'&openid='.$data['openid'].'&lang=zh_CN';
        $json = $this->ci->extend->getRemoteData($url);
        $json = json_decode($json, true);
        /*
         * 错误时返回
         * {"errcode":40003,"errmsg":" invalid openid "}
         * 正确时返回
         * {
         *    "openid":" OPENID",
         *    "nickname": NICKNAME,
         *    "sex":"1",
         *    "province":"PROVINCE"
         *    "city":"CITY",
         *    "country":"COUNTRY",
         *    "headimgurl": "http://wx.qlogo.cn/mmopen/g3MonUZtNHkdmzicIlibx6iaFqAc56vxLSUfpb6n5WKSYVY0ChQKkiaJSgQ1dZuTOgvLLrhJbERQQ4eMsv84eavHiaiceqxibJxCfHe/46",
         *    "privilege":[
         *          "PRIVILEGE1"
         *          "PRIVILEGE2"
         *    ],
         *    "unionid": "o6_bmasdasdsad6_2sgVt7hMZOPfL"
         * }
         */
        if(!empty($json['openid']))
        {
            $this->ci->session->set_userdata('fwhWxInfo'.$this->pubId, $json);
        }else {
            $this->error('AppId:'.$this->pubId.'json_error====='.var_export($json,true),'api_log.text');
        }
    }

    /**
     * 获取可拿是否关注公众号信息的access_token
     * @param bool|true $realTime
     * @return bool
     */
    public function _getSubscribeAccessToken($realTime=false)
    {
        $host = $_SERVER['HTTP_HOST'];
        if($host=='127.0.0.1' || $host=='::1' || $host=='localhost' || strpos($host, '192.168.')!==false || strpos($host, 'anzeen.net')!==false || strpos($host, 'anzeen.cn')!==false)
        {
            exit("本地不允许拿: subscribeAccessToken");
        }
        
        $tokenFileName = $this->config['tokenFileName'];
        $access_token = $this->ci->cache->redis->get($tokenFileName);
        if(!$access_token || $realTime)
        {
            if($this->officialAccountAppId){
                $access_token = $this->getNewOauthAccessToken($realTime);
                $this->error('json==='.$access_token,'api_log.txt');
                if(empty($access_token)){
                    return false;
                }

            }else{
                $url    = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='. $this->config['appId'] .'&secret='. $this->config['appSecret'];
                $json   = $this->ci->extend->getRemoteData($url);
                //$this->error('json==='.$json,'api_'.$officialAccountId.'.txt');
                $json   = json_decode($json, true);
                if(empty($json['access_token']))
                    return false;
                $access_token   = $json['access_token'];
                $expiresTime    = $json['expires_in'] - 600;
                $res = $this->ci->cache->redis->save($tokenFileName, $access_token, $expiresTime);
                if($res == false){
                    $this->saveAccessToken($tokenFileName,$access_token ,$expiresTime);
                }
            }
        }
        return $access_token;
    }

    /**
     * 获取jsapi ticket
     */
    private function _getJsTicket()
    {
        if($this->_isTestHost()){
            $ticket = 'a:3:{s:4:"time";i:1445058151;s:3:"ttl";i:7195;s:4:"data";a:2:{s:6:"ticket";s:86:"bxLdikRXVbTPdHSM05e5u6pAcAn6qsOSEnt-BmY4ZA7A16RiipvVbvxZdYQoLLKUh-ky3hBV2nUrM2zozPIYAA";s:7:"expires";i:1445065345;}}';
            return $ticket;
        }

        $jsTicketFileName = $this->config['jsTicketFileName'];
        $ticket = $this->ci->cache->redis->get($jsTicketFileName);
        if(!$ticket)
        {
            $token = $this->_getSubscribeAccessToken();
            $url    = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='. $token .'&type=jsapi';
            $json   = $this->ci->extend->getRemoteData($url);
            $json   = json_decode($json, true);
            if(empty($json['ticket']))
                return false;

            $ticket         = $json['ticket'];
            $expiresTime    = $json['expires_in'] - 5;
            $this->ci->cache->redis->save($jsTicketFileName, $ticket, $expiresTime);
        }

        return $ticket;
    }

    /**
     * 验证微信绑定
     */
    public function checkSignature($token)
    {
        $signature  = $this->ci->input->get('signature');
        $timestamp  = $this->ci->input->get('timestamp');
        $nonce      = $this->ci->input->get('nonce');

        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 分析数据
     */
    public function analysis($token,$encodingAesKey,$appId)
    {
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];//取post数据
        $postStr = $this->msgCrypt($postStr,$token,$encodingAesKey,$appId, 'decode');

        if (!empty($postStr)){
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $data = $postObj;
        }else{
            $data=array();
        }

        return $data;
    }

    /**
     * 发送图文消息
     * @param $wxMsg
     * @param $data
     * @param $token
     * @param $encodingAesKey
     * @param $appId
     */
    public function sendNews($wxMsg, $data,$token,$encodingAesKey,$appId)
    {
        $xml = '<xml>
                <ToUserName><![CDATA['. $wxMsg->FromUserName .']]></ToUserName>
                <FromUserName><![CDATA['. $wxMsg->ToUserName .']]></FromUserName>
                <CreateTime>'. time() .'</CreateTime>
                <MsgType><![CDATA[news]]></MsgType>
                <ArticleCount>'. count($data) .'</ArticleCount>
                <Articles>';
        foreach($data as $row){
            $xml .= '<item>
                    <Title><![CDATA['. $row->title .']]></Title>
                    <Description><![CDATA['. $row->desc .']]></Description>
                    <PicUrl><![CDATA['. $row->src .']]></PicUrl>
                    <Url><![CDATA['. $row->url .'&dyh='.$wxMsg->FromUserName.']]></Url>
                    </item>';
        }
        $xml .= '</Articles>
                </xml>';
        file_put_contents('/tmp/subscribe.txt',"--|".$xml."|--",FILE_APPEND);
        $xml = $this->msgCrypt($xml,$token,$encodingAesKey,$appId);

        exit($xml);
    }

    /**
     * 发送文字消息
     * @param $wxMsg
     * @param $content
     * @param $token
     * @param $encodingAesKey
     * @param $appId
     */
    public function sendText($wxMsg, $content,$token,$encodingAesKey,$appId)
    {
        $xml = '<xml>
                <ToUserName><![CDATA['. $wxMsg->FromUserName .']]></ToUserName>
                <FromUserName><![CDATA['. $wxMsg->ToUserName .']]></FromUserName>
                <CreateTime>'. time() .'</CreateTime>
                <MsgType><![CDATA[text]]></MsgType>
                <Content><![CDATA['. $content .']]></Content>
                </xml>';
        file_put_contents("/tmp/wx1.txt",$xml);
        $xml = $this->msgCrypt($xml,$token,$encodingAesKey,$appId);
        file_put_contents("/tmp/wx2.txt",$xml);
        exit($xml);
    }

    /**
     * 发送图片消息
     * @param $wxMsg
     * @param $mediaId
     * @param $token
     * @param $encodingAesKey
     * @param $appId
     */
    public function sendImg($wxMsg,$mediaId,$token,$encodingAesKey,$appId)
    {
        $xml = '<xml>
                <ToUserName><![CDATA['. $wxMsg->FromUserName .']]></ToUserName>
                <FromUserName><![CDATA['. $wxMsg->ToUserName .']]></FromUserName>
                <CreateTime>'. time() .'</CreateTime>
                <MsgType><![CDATA[image]]></MsgType>
                <Image>
                <MediaId><![CDATA['. $mediaId .']]></MediaId>
                </Image>
                </xml>';
        $xml = $this->msgCrypt($xml,$token,$encodingAesKey,$appId);
        exit($xml);
    }

    /**
     * 发送音频消息
     * @param $wxMsg
     * @param $mediaId
     * @param $token
     * @param $encodingAesKey
     * @param $appId
     */
    public function sendAudio($wxMsg,$mediaId,$token,$encodingAesKey,$appId)
    {
        $xml = '<xml>
                <ToUserName><![CDATA['. $wxMsg->FromUserName .']]></ToUserName>
                <FromUserName><![CDATA['. $wxMsg->ToUserName .']]></FromUserName>
                <CreateTime>'. time() .'</CreateTime>
                <MsgType><![CDATA[voice]]></MsgType>
                <Voice>
                <MediaId><![CDATA['. $mediaId .']]></MediaId>
                </Voice>
                </xml>';
        $xml = $this->msgCrypt($xml,$token,$encodingAesKey,$appId);

        file_put_contents("/tmp/subscribe.txt","voice-------------|".$xml."|--",FILE_APPEND);
        exit($xml);
    }

    /**
     * 发送视频消息
     * @param $wxMsg
     * @param $mediaId
     * @param $title
     * @param $description
     * @param $token
     * @param $encodingAesKey
     * @param $appId
     */
    public function sendVideo($wxMsg,$mediaId,$title,$description,$token,$encodingAesKey,$appId)
    {
        $xml = '<xml>
                <ToUserName><![CDATA['. $wxMsg->FromUserName .']]></ToUserName>
                <FromUserName><![CDATA['. $wxMsg->ToUserName .']]></FromUserName>
                <CreateTime>'. time() .'</CreateTime>
                <MsgType><![CDATA[video]]></MsgType>
                <Video>
                <MediaId><![CDATA['.$mediaId.']]></MediaId>
                <Title><![CDATA['.$title.']]></Title>
                <Description><![CDATA['.$description.']]></Description>
                </Video>
                </xml>';
        $xml = $this->msgCrypt($xml,$token,$encodingAesKey,$appId);
        exit($xml);
    }

    /**
     * 发送歌曲消息
     * @param $wxMsg
     * @param $mediaId
     * @param $url
     * @param $title
     * @param $description
     * @param $token
     * @param $encodingAesKey
     * @param $appId
     */
    public function sendMusic($wxMsg,$mediaId,$url,$title,$description,$token,$encodingAesKey,$appId)
    {
        $xml = '<xml>
                <ToUserName><![CDATA['. $wxMsg->FromUserName .']]></ToUserName>
                <FromUserName><![CDATA['. $wxMsg->ToUserName .']]></FromUserName>
                <CreateTime>'. time() .'</CreateTime>
                <MsgType><![CDATA[music]]></MsgType>
                <Music>
                <Title><![CDATA['.$title.']]></Title>
                <Description><![CDATA['.$description.']]></Description>
                <MusicUrl><![CDATA['.$url.']]></MusicUrl>
                <HQMusicUrl><![CDATA['.$url.']]></HQMusicUrl>
                <ThumbMediaId><![CDATA['.$mediaId.']]></ThumbMediaId>
                </Music>
                </xml>';
        $xml = $this->msgCrypt($xml,$token,$encodingAesKey,$appId);
        exit($xml);
    }

    /**
     * 图片上传函数
     * @param $serverId
     * @param $dir
     * @param int $width
     * @param int $height
     * @return bool|string
     */
    function uploadImg($serverId,$dir,$width=640,$height = 1080){
        if($this->_isTestHost()){
            echo '测试服务器无法上传图片！';
            return false;
        }
        $accessToken = $this->_getSubscribeAccessToken();
        $img = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=".$accessToken."&media_id=".$serverId;
        $dir = FCPATH.$dir;

        $size_src=getimagesize($img);
        $w=$size_src['0'];
        $h=$size_src['1'];
        //print_r($size_src);
        $image = imagecreatefromjpeg($img);
        $thumb = imagecreatetruecolor ($width, $height);
        imagecopyresized ($thumb, $image, 0, 0, 0, 0, $width, $height, $w, $h);
        $fileNaem = rand(100,999);
        imagejpeg($thumb, $dir.'/'.$fileNaem.".jpg");

        imagedestroy($thumb);
        imagedestroy($image);
        return $dir.'/'.$fileNaem.".jpg";
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
            file_put_contents('ss1.txt',$error_code);
        }else {
            file_put_contents('ss2'.$type.'.txt',$xml);
        }
        return $xml;
    }

    //创建菜单json
    public function createMenu($menu)
    {
        if($this->_isTestHost()){
            echo '测试服务器无法生成菜单';
            return false;
        }
        //把中文处理一下，不然json_encode后中文会变成\uxxx的形式，微信不支持该形式
        //解决方法：先把name url_encode一下，后面json_encode之后再url_decode一下
        foreach($menu['button'] as $key => $row)
        {
            $menu['button'][$key]['name'] = urlencode($row['name']);
            if(!empty($row['sub_button']))
                foreach($row['sub_button'] as $k => $item)
                {
                    $menu['button'][$key]['sub_button'][$k]['name'] = urlencode($item['name']);
                }
        }
        $json = urldecode(json_encode($menu));
        $json = str_replace('\/','/',$json);//替换url的\/为/

        //发送自定义菜单
        $accessToken = $this->_getSubscribeAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='. $accessToken;
        //print_r($json);
        $rs = $this->ci->extend->getRemoteData($url, $json);
        return $rs;
    }

    //删除菜单
    public function deleteMenu()
    {
        if($this->_isTestHost()){
            echo '测试服务器无法生成菜单';
            return false;
        }
        //把中文处理一下，不然json_encode后中文会变成\uxxx的形式，微信不支持该形式
        //解决方法：先把name url_encode一下，后面json_encode之后再url_decode一下

        //发送自定义菜单
        $accessToken = $this->_getSubscribeAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/delete?access_token='. $accessToken;
        $rs = $this->ci->extend->getRemoteData($url);
        return $rs;
    }

    public function sendBonus($data,$officialAccount = false)
    {
        $chkData = array(
            'mch_billno',   //商户订单号
            //'sub_mch_id', //子商户号
            'nick_name',    //提供方名称,
            'send_name',    //商户名称
            're_openid',    //用户openid
            'total_amount', //付款金额
            'min_value',    //最小红包金额
            'max_value',    //最大红包金额
            'total_num',    //红包发放总人数
            'wishing',      //红包祝福语
            'act_name',     //活动名称
            //'remark',     //备注信息
            'logo_imgurl',  //商户logo的url
            //'share_content',  //分享文案
            //'share_url',  //分享链接
            //'share_imgurl',   //分享的图片
        );
        foreach ($chkData as $key)
        {
            if(!array_key_exists($key, $data))
            {
                exit("data \"{$key}\" not exists");
            }
        }

        //var_dump($this->ci->weixinConfig);

        //以下为非必填参数
        //随机字符串
        if(empty($data['nonce_str']))
            $data['nonce_str'] = md5(microtime(true));
        //商户号
        if(empty($data['mch_id']))
            $data['mch_id'] = $officialAccount !== false ? $officialAccount->mchId : $this->config['mchId'];
        ////公众账号appid
        if(empty($data['wxappid']))
            $data['wxappid'] = $officialAccount !== false ? $officialAccount->appId : $this->config['appId'];
        //调用接口的机器Ip地址
        if(empty($data['client_ip']))
            $data['client_ip'] =  gethostbyname(isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : ''));

        ksort($data);
        $str = '';

        $i = 0;
        foreach($data as $key => $val)
        {
            if($i !== 0)
                $str .= '&';
            if(!empty($val))
                $str .= $key .'='. $val;
            $i++;
        }
        $str .= '&key='.$this->config['payKey'];
        //echo $str."\n\n\n";
        $str = strtoupper(md5($str));

        $data['sign'] = $str;

        //组成xml字符串
        $xml = '<xml>';
        foreach($data as $key => $val)
        {
            $xml .= "<{$key}><![CDATA[{$val}]]></{$key}>";
        }
        $xml .= '</xml>';


        $rs = $this->bonusGetRemoteData($xml,$officialAccount);
        $rs = str_replace('<![CDATA[', '', $rs);
        $rs = str_replace(']]>', '', $rs);

        $json = (array)simplexml_load_string($rs);
        @file_put_contents(APPPATH.'logs/bonus_log.txt',date("Y-m-d H:i:s").'-|-'.$data['re_openid'].'-|-'.json_encode($json,JSON_UNESCAPED_UNICODE)."|;\n\n",FILE_APPEND);

        if($json['return_code']=='SUCCESS')
        {
            $rs = array(
                'errcode'=>0,
                'msg'=>'success',
                'data'=>$json
            );
        }else{
            $rs =array(
                'errcode' => $json['err_code'],
                'msg' => $json['err_code_des'],
                'data'=>$json
            );
        }

        return $rs;
    }

    private function bonusGetRemoteData($xml,$officialAccount = false)
    {
        //var_dump($officialAccount);
        $apiClientCert = $officialAccount !== false ? $officialAccount->apiClientCert : $this->config['apiClientCert'];
        $apiClientKey = $officialAccount !== false ? $officialAccount->apiClientKey : $this->config['apiClientKey'];
        $rootCa = $officialAccount !== false ? $officialAccount->rootCa : $this->config['rootCa'];
        $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); // 对认证证书来源的检查
        //curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE); // 从证书中检查SSL加密算法是否存在
        //curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)'); //模拟用户使用的浏览器
        curl_setopt($curl, CURLOPT_SSLCERT, $apiClientCert);
        curl_setopt($curl, CURLOPT_SSLKEY, $apiClientKey);
        curl_setopt($curl, CURLOPT_CAINFO, $rootCa);

        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $xml);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        $html = curl_exec($curl);
        curl_close($curl);

        return $html;
    }

    /**
     * 图片上传函数
     * @param $serverId
     * @param $officialId
     * @param $officialAccountId
     * @param int $width
     * @param int $height
     * @return bool|string
     */
    function uploadMedia($serverId,$officialId,$officialAccountId,$width=640,$height = 1080){
        if($this->_isTestHost()){
            echo '测试服务器无法上传图片！';
            return false;
        }
        $accessToken = $this->_getSubscribeAccessToken();
        $img = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=".$accessToken."&media_id=".$serverId;
        //echo FCPATH;
        if(!is_dir(FCPATH.'img/upload')){
            mkdir(FCPATH.'img/upload');
        }

        if(!is_dir(FCPATH.'img/upload/images')){
            mkdir(FCPATH.'img/uploads/images', 0777, true);
        }
        if(!is_dir(FCPATH.'img/uploads/images/'.date("Y-m-d")."/".$officialId)){
            mkdir(FCPATH.'img/uploads/images/'.date("Y-m-d")."/".$officialId);
        }
        if(!is_dir(FCPATH.'img/uploads/images/'.date("Y-m-d")."/".$officialId."/".$officialAccountId)){
            mkdir(FCPATH.'img/uploads/images/'.date("Y-m-d")."/".$officialId."/".$officialAccountId);
        }
        if(!is_dir(FCPATH.'img/uploads/images/'.date("Y-m-d")."/".$officialId."/".$officialAccountId.'/'.date("Y-m-d"))){
            mkdir(FCPATH.'img/uploads/images/'.date("Y-m-d")."/".$officialId."/".$officialAccountId.'/'.date("Y-m-d"));
        }

        $dir = FCPATH.'img/uploads/images/'.date("Y-m-d")."/".$officialId."/".$officialAccountId.'/'.date("Y-m-d");
        $fileDir = 'img/uploads/images/'.date("Y-m-d")."/".$officialId."/".$officialAccountId.'/'.date("Y-m-d");

        $size_src=getimagesize($img);
        $w=$size_src['0'];
        $h=$size_src['1'];
        //print_r($size_src);
        $image = imagecreatefromjpeg($img);
        $thumb = imagecreatetruecolor ($width, $height);
        imagecopyresized ($thumb, $image, 0, 0, 0, 0, $width, $height, $w, $h);
        $fileNaem = rand(100,999);
        imagejpeg($thumb, $dir.'/'.$fileNaem.".jpg");

        imagedestroy($thumb);
        imagedestroy($image);
        return $fileDir.'/'.$fileNaem.".jpg";
    }

    function downloadMedia($mediaId,$officialId,$officialAccountId,$fileType="jpg"){

        $accessToken = $this->_getSubscribeAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/media/get?access_token=".$accessToken."&media_id=".$mediaId;

        $mediaName = $this->createGuid();

        $dir = '/img/upload/media/'.$officialId."/".$officialAccountId."/".date("Ymd");
        $name = $mediaName.'.'.$fileType;

        $remoteUploadUrl = $this->manageHost."/manage/putfile?dir=".urlencode($dir)."&name=".$name.'&oid='.$officialId.'&oaid='.$officialAccountId."&url=".urlencode($url);
        $this->error("url:".$remoteUploadUrl,'putfile.txt');
        $mp3 = $this->getRemoteDataJson($remoteUploadUrl,array());
        //$return = new stdClass();
        $return = $dir."/".$name;
        if(!empty($mp3)){
            $return = $return . "|" . $mp3;
        }
        return $return;

        /*$accessToken = $this->_getSubscribeAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/media/get?access_token=".$accessToken."&media_id=".$mediaId;

        $mediaName = $this->createGuid();
        $mediaFile = 'img/upload/media/'.$officialId."/".$officialAccountId."/".date("Ymd").'/'.$mediaName.'.'.$fileType;
        $dir = str_replace("manage/","",FCPATH);
        $fullPathFile = $dir.$mediaFile;
        $this->createDir($mediaFile);
        $media = file_get_contents($url);
        file_put_contents($fullPathFile,$media);
        return "/".$mediaFile;*/
    }

    function downloadLongTimeMedia($mediaId,$officialId,$officialAccountId,$fileType="jpg"){
        $accessToken = $this->_getSubscribeAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/material/get_material?access_token=".$accessToken;

        $mediaName = $this->createGuid();
        $mediaFile = 'img/upload/media/'.$officialId."/".$officialAccountId."/".date("Ymd").'/'.$mediaName.'.'.$fileType;
        $dir = str_replace("manage/","",FCPATH);
        $fullPathFile = $dir.$mediaFile;
        $this->createDir($mediaFile);
        $media = $this->getRemoteDataJson($url,json_encode(array('media_id'=>$mediaId)));
        $result = @json_decode($media);
        if(isset($result->down_url)){
            return $result;
        }
        /*print_r(json_encode(array('media_id',$mediaId)));
        var_dump($media);exit;*/
        file_put_contents($fullPathFile,$media);
        return "/".$mediaFile;
    }


    function uploadMaterial($filePath,$accessToken,$type){

        //$url="https://api.weixin.qq.com/cgi-bin/material/add_material?access_token={$accessToken}&type=".$type;
        $url="https://api.weixin.qq.com/cgi-bin/media/upload?access_token={$accessToken}&type=".$type;
        $ch1 = curl_init();
        $cfile = curl_file_create($filePath);
        
        //$real_path=str_replace("/", "\\", $real_path);
        $data= array("media"=>$cfile);
        curl_setopt($ch1, CURLOPT_URL,$url);
        curl_setopt($ch1, CURLOPT_POST,1);
        curl_setopt ( $ch1, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $ch1, CURLOPT_SSL_VERIFYPEER, FALSE );
        curl_setopt ( $ch1, CURLOPT_SSL_VERIFYHOST, false );
        curl_setopt ($ch1,CURLOPT_POSTFIELDS,$data);
        $result = curl_exec($ch1);
        if(curl_errno($ch1)==0){
            curl_close($ch1);
            $result=json_decode($result,true);
            return $result['media_id'];
        }else {
            return false;
        }
    }
    
    function printAccessToken(){
        $accessToken = $this->_getSubscribeAccessToken();
        var_dump($accessToken);
    }

    /**
     * 取微支付参数
     * @param string $trade_no		订单号
     * @param int $total_fee	总价格（单位为分）
     * @param string $body			订单描述
     * @param string $notify_url	异步通知url
     * @param string $JS_API_CALL_URL	获取access_token过程中的跳转uri，通过跳转将code传入jsapi支付页面(在没有取到openid的情况下需要该参数)
     * @return mixed
     */
    public function getPayParams($openid='',$trade_no, $total_fee, $body, $notify_url='', $JS_API_CALL_URL='')
    {
        if(!empty($notify_url))
            $this->config['notifyUrl'] = $notify_url;

        include_once("Weixin/WxPayPubHelper/WxPayPubHelper.php");
        //初始化微支付的参数
        if(!empty($notify_url))
            $this->config['notify_url'] = $notify_url;

        if(!empty($JS_API_CALL_URL))
            $this->config['JS_API_CALL_URL'] = $JS_API_CALL_URL;
        WxPayConf_pub::setParams($this->config);

        //使用jsapi接口
        $jsApi = new JsApi_pub();

        $wxInfo = $this->ci->session->userdata('fwhWxInfo'.$this->pubId);
        $wxInfo['openid']=$openid;
//        if(empty($wxInfo['openid']))
//        {
//            //=========步骤1：网页授权获取用户openid============
//            //通过code获得openid
//            $code = $this->ci->input->get('code');
//            if (empty($code))
//            {
//                //触发微信返回code码
//                $url = $jsApi->createOauthUrlForCode(WxPayConf_pub::$JS_API_CALL_URL);
//                header("Location: $url");
//            }else
//            {
//                //获取code码，以获取openid
//                $jsApi->setCode($code);
//                $openid = $jsApi->getOpenId();
//            }
//        }else{
//            $openid = $wxInfo['openid'];
//        }

        //=========步骤2：使用统一支付接口，获取prepay_id============
        //使用统一支付接口
        $unifiedOrder = new UnifiedOrder_pub();
        //设置统一支付接口参数
        //设置必填参数
        $unifiedOrder->setParameter('openid', $openid);
        $unifiedOrder->setParameter('body', $body);//商品描述
        //自定义订单号，此处仅作举例
        $timeStamp = time();
        $out_trade_no = WxPayConf_pub::$APPID."$timeStamp";
        $unifiedOrder->setParameter('out_trade_no', $trade_no);//商户订单号
        $unifiedOrder->setParameter('total_fee', $total_fee);//总金额
        $unifiedOrder->setParameter('notify_url', WxPayConf_pub::$NOTIFY_URL);//通知地址
        $unifiedOrder->setParameter('trade_type', 'JSAPI');//交易类型
        //非必填参数，商户可根据实际情况选填
        //$unifiedOrder->setParameter("sub_mch_id","XXXX");//子商户号
        //$unifiedOrder->setParameter("device_info","XXXX");//设备号
        //$unifiedOrder->setParameter("attach","XXXX");//附加数据
        //$unifiedOrder->setParameter("time_start","XXXX");//交易起始时间
        //$unifiedOrder->setParameter("time_expire","XXXX");//交易结束时间
        //$unifiedOrder->setParameter("goods_tag","XXXX");//商品标记
        //$unifiedOrder->setParameter("openid","XXXX");//用户标识
        //$unifiedOrder->setParameter("product_id","XXXX");//商品ID
        $prepayResult = $unifiedOrder->getPrepayId();
        if($prepayResult['success']){
            $prepay_id = $prepayResult['prepayId'];
        }else{
            return $prepayResult;
        }
        //=========步骤3：使用jsapi调起支付============
        $jsApi->setPrepayId($prepay_id);
        $jsApiParameters = $jsApi->getParameters();
        return $jsApiParameters;
    }

    //二维码支付 返回二维码路径
    public function getPayParamsCode($openid,$trade_no, $total_fee, $body, $notify_url='', $JS_API_CALL_URL='')
    {
        include_once("Weixin/WxPayPubHelper/WxPayPubHelper.php");
        //初始化微支付的参数
        if(!empty($notify_url))
            $this->config['notify_url'] = $notify_url;

        if(!empty($JS_API_CALL_URL))
            $this->config['JS_API_CALL_URL'] = $JS_API_CALL_URL;
        WxPayConf_pub::setParams($this->config);

        //=========步骤2：使用统一支付接口，
        //使用统一支付接口
        $unifiedOrder = new UnifiedOrder_pub();
        //设置统一支付接口参数

        $unifiedOrder->setParameter('openid', $openid);
        $unifiedOrder->setParameter('body', $body);//商品描述
        //自定义订单号，此处仅作举例
        $timeStamp = time();
        $unifiedOrder->setParameter('out_trade_no', $trade_no);//商户订单号
        $unifiedOrder->setParameter('total_fee', $total_fee);//总金额
        $unifiedOrder->setParameter('notify_url', WxPayConf_pub::$NOTIFY_URL);//通知地址
        $unifiedOrder->setParameter('trade_type', 'NATIVE');//交易类型
        //非必填参数，商户可根据实际情况选填
        //$unifiedOrder->setParameter("sub_mch_id","XXXX");//子商户号
        //$unifiedOrder->setParameter("device_info","XXXX");//设备号
        //$unifiedOrder->setParameter("attach","XXXX");//附加数据
        //$unifiedOrder->setParameter("time_start","XXXX");//交易起始时间
        //$unifiedOrder->setParameter("time_expire","XXXX");//交易结束时间
        //$unifiedOrder->setParameter("goods_tag","XXXX");//商品标记
        //$unifiedOrder->setParameter("openid","XXXX");//用户标识
        //$unifiedOrder->setParameter("product_id","XXXX");//商品ID
        $prepayResult = $unifiedOrder->getPrepayId();
        return $prepayResult;
    }


    //订单支付回调
    public function notify()
    {
        include_once("Weixin/WxPayPubHelper/WxPayPubHelper.php");
        //初始化参数
        WxPayConf_pub::setParams($this->config);
        //使用通用通知接口
        $notify = new Notify_pub();

        //存储微信的回调
        $xml = file_get_contents('php://input');
        if(empty($xml)){
            return false;
        }
        $notify->saveData($xml);
        //验证签名，并回应微信。
        //对后台通知交互时，如果微信收到商户的应答不是成功或超时，微信认为通知失败，
        //微信会通过一定的策略（如30分钟共8次）定期重新发起通知，
        //尽可能提高通知的成功率，但微信不保证通知最终能成功。
        $checkSign = $notify->checkSign();
        if($checkSign == FALSE){
            $notify->setReturnParameter("return_code","FAIL");//返回状态码
            $notify->setReturnParameter("return_msg","签名失败");//返回信息
        }else{
            $notify->setReturnParameter("return_code","SUCCESS");//设置返回码
        }
        $returnXml = $notify->returnsXml();
        //echo $returnXml;


        //以log文件形式记录回调信息
        $log_name = APPPATH.'logs/pay_notify_'. date('Ymd') .'.log';//log文件路径
        $this->payLog($log_name,"【接收到的notify通知】:\n".$xml."\n");

        if($checkSign == TRUE){
            $this->error("签名成功");
            if ($notify->data["return_code"] == "FAIL") {
                //此处应该更新一下订单状态，商户自行增删操作
                $this->payLog($log_name,"【通信出错】:\n".$xml."\n");
            }elseif($notify->data["result_code"] == "FAIL"){
                //此处应该更新一下订单状态，商户自行增删操作
                $this->payLog($log_name,"【业务出错】:\n".$xml."\n");
            }else{
                //此处应该更新一下订单状态，商户自行增删操作
                $this->payLog($log_name,"【支付成功】:\n".$xml."\n");
                return true;
            }

        }
        //签名失败
        return false;
    }

    //订单支付回调
    public function chanotify( $out_trade_no,$transaction_id)
    {
        include_once("Weixin/lib/WxPay.Api.php");
        require_once "Weixin/example/WxPay.Config.php";
        require_once 'Weixin/example/log.php';

        $input = new WxPayOrderQuery();
        $input->SetTransaction_id($transaction_id);
        $input->SetOut_trade_no($out_trade_no);
        $config = new WxPayConfig();
        return WxPayApi::orderQuery($config, $input);
    }

    private function payLog($file,$word)
    {
        $fp = fopen($file,"a");
        flock($fp, LOCK_EX) ;
        fwrite($fp,"执行日期：".strftime("%Y-%m-%d-%H：%M：%S",time())."\n".$word."\n\n");
        flock($fp, LOCK_UN);
        fclose($fp);
    }

    public function createGuId(){
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

    private function create_guid_section($characters){
        $return = "";
        for($i=0; $i<$characters; $i++)
        {
            $return .= dechex(mt_rand(0,15));
        }
        return $return;
    }

    function createDir($file){
        $paths = explode("/",$file);
        $dir = $_SERVER['DOCUMENT_ROOT'];
        $last = count($paths) - 1;
        unset($paths[$last]);
        foreach ($paths as $path){
            if(!empty($path)){
                $dir = $dir.'/'.$path;
                if (!is_dir($dir))
                    mkdir($dir, 0777, true);
            }

        }
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

    function error($msg,$filename = ''){
        $errors = debug_backtrace();
        //if(empty($filename)) $filename = 'api_'.$this->officialAccountId.'_'.$this->openId.'.txt';
        if(empty($filename)) $filename = 'api_logs.txt';
        file_put_contents(APPPATH."logs/".$filename,'------ '.date("Y-m-d H:i:s")."\n",FILE_APPEND);
        file_put_contents(APPPATH."logs/".$filename,$errors[0]['file']."\n".$errors[1]['function']."\n"."第".$errors[0]['line']."行\n".$errors[0]['class']."\n".__FUNCTION__."\n第".__LINE__."行\n",FILE_APPEND);
        file_put_contents(APPPATH."logs/".$filename,$msg."\n\n",FILE_APPEND);
    }
    public function xmlToArray($xml)
    {
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $values;
    }

    function saveAccessToken($tokenFileName,$access_token,$expiresTime){
        $data[] = $access_token;
        $data[] = $expiresTime + 600;
        file_put_contents(rtrim($_SERVER['DOCUMENT_ROOT'],'public')."cache/accesstoken/".$tokenFileName.".txt",json_encode($data));   
    }

}
