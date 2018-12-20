<?php

/**
 * writeLog 打印日志
 * @param $content 内容
 * @param $filepath 文件路径
 * @return bool
 * @auth kouweihong
 * @time ${YEAR} ${DATE}
 */
function writeLog($content, $filepath, $isLog = true)
{
    //是否打印日志 默认是打印
    if (!$isLog) return false;
    if (!$content) {
        return FALSE;
    }
    

    $filepath.'../manage/logs/'.$filepath;
    $client = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'unknown';
    $server = isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : 'unknown';
    $request_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : 'unknown';
    $http_user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'unknown';
    $http_referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'unknown';
    //初始化本条日志串
    $str = sprintf("[%s][client=%s][server=%s][request_uri=%s][user_agent=%s][http_referer=%s]", date("Y-m-d H:i:s:ms"), $client, $server, $request_uri, $http_user_agent, $http_referer);
    if (!is_dir(pathinfo($filepath)['dirname'])) {
        mkdir(pathinfo($filepath)['dirname'], 0777, true);
    }

    return write_log($str . "\n" . $content . "\n\n", $filepath);
}


function write_log($content, $filename)
{
    $content = "[" . date('Y-m-d H:i:s') . "] " . $content;
    if (@$fp = @fopen($filename, 'a')) {
        @flock($fp, LOCK_EX);
        @fwrite($fp, $content . "\n");
        @fclose($fp);
    }
}

/**
 *
 * @author zhangjunzj@didichuxing.com
 *
 * @param mixed $code =0 0表示请求成功， 1表示请求失败，
 * @param mixed $data array
 * @param mixed $message
 * @param mixed $http_code '200'
 *
 * @return mixed
 */
function output_response($code = 0, $data = array(), $message = 'ok',$count = array())
{
    $output['err_code'] = $code;
    $output['msg'] = $message;

    if (count($data)) {
        $output['data'] = $data;
    }
    
    if(empty($count)) {
        foreach ($count as $k => $v) {
            $output[$k] = $v;
        }
    }

    echo json_encode($output);
    die;
}


//删除数组中的某个值 返回字符串
function unsetArrVal($arr, $str='')
{
    $data = '';
    foreach ($arr as $value) {
        if(0 !=strcmp($value, $str)) {
            if($data == '') {
                $data.= $value;
            }else {                
                $data.= ','.$value;
            }            
        }
    }
    return $data;
}

//判断是否是正整数
function isInt($int)
{
    if(preg_match("/^[1-9][0-9]*$/", $int) && $int1 !=0) {
        return true;    
    }
    return false;
}

//base64多图上传
function base64_upload($aData) {
    if ($aData == '' || $aData == 'undefined') {
        return ['code'=>1,'str'=>'请选择图片'];
    }
    if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $aData, $result)) {
        $base64_body = substr(strstr($aData, ','), 1);
        empty($aExt) && $aExt = $result[2];
    } else {
        $base64_body = $aData;
    }

    if(!in_array($aExt,array('jpg','gif','png','jpeg'))){
        return ['code'=>1,'str'=>'图片格式错误'];
    }
    $hasPhp=base64_decode($base64_body);
    if (strpos($hasPhp, '<?php') !==false) {
        return ['code'=>1,'str'=>'图片错误'];
    }
    //不存在则上传并返回信息

    $saveName = uniqid();
    $savePath = '/img/upload/'.date("Ymd");
    $path =  '/' . $saveName . '.' . $aExt;
    //本地上传

    if(!file_exists('..'.$savePath)){
        mkdir('..'.$savePath  . '/', 0777,true);
    }
    $data = base64_decode($base64_body);
    $rs = file_put_contents('..' . $savePath . $path, $data);

    if ($rs) {
        return $savePath . $path;
    } else {
        return false;
    }

}