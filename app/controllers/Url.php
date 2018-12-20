<?php
class Url extends AZ_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Home_model', 'home');
    }
     
    function index(){
        $data=$this->home->information();
        $url=$this->home->xiazaiurl();
        $iosurl=isset($url->ios) && $url->ios!=''?$url->ios:$data->iosDownload;
        echo "<script>
    var u = navigator.userAgent;
    var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Adr') > -1; //android终端
    var isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端

    if (isiOS) {
        window.location.href ='".$iosurl."';
    }else{
        window.location.href = '".$data->androidDownload."';
    }
    
</script>";
       
    }
   
    
}