<!DOCTYPE html>
<html style="height: 100%;">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <meta name="format-detection" content="email=no">
    <link rel="stylesheet" type="text/css" href="/css/index.css?v=1">
    <link rel="stylesheet" href="/css/layui.css">
	<title><?php echo $title; ?>注册</title>
	<script>
		var dpr = 1 / window.devicePixelRatio;
		document.write('<meta name="viewport" content="width=device-width,user-scalable=no,initial-scale='+dpr+',minimum-scale='+dpr+',maximum-scale='+dpr+'" />');
		var fz = document.documentElement.clientWidth / 10;
		document.getElementsByTagName('html')[0].style.fontSize = fz + 'px';
	</script>
  <style type="text/css">
  .layui-m-layercont{font-size: 0.4rem;}
</style>
	</head>
	<body style="background: url('/img/bj.jpg') #fe4041; background-repeat: no-repeat; background-size: 100%; height: 100%;"><input type="hidden" id="code" value="<?php echo isset($code)?$code:'';?>" />
          <section class="enrollBox">
                <ul>
                      <li><input type="text" id="mobile" required placeholder="手机号"></li>
                      <li><input type="password" id="password" required  placeholder="密码"></li>
                      <li>
                        <input type="text" required id="code_input" placeholder="图形验证码" style="width:65%;">
                        <div id="v_container"></div>
                      </li>
                      <li>
                        <input type="text" required id="verify" placeholder="短信验证码" style="width:65%;">
                        <button id="yanZengMa" onclick="sendemail()">获取验证码</button>
                      </li>
                </ul>
                <div class="enrollBox-text">
                      <label><input type="checkbox" checked id="xieyi"><span>我已阅读并同意</span><a href="/home/instructions">《<?php echo $title; ?>注册协议》</a></label>
                </div>
                <div class="enrollBox-button">
                      <button id="my_button">立即注冊</button>
                </div>
          </section>
	</body>
      <script type="text/javascript" src="/js/jquery-2.1.1.min.js"></script>
      <script type="text/javascript" src="/js/gVerify.js"></script>
      <script src="/js/layer/mobile/layer.js"></script>
      <script type="text/javascript" src="/js/plugins/layui/layui.js"></script>
    <script>
      var verifyCode = new GVerify("v_container");
      document.getElementById("my_button").onclick = function(){
            var res = verifyCode.validate(document.getElementById("code_input").value);


          var mobile=$("#mobile").val();
          var verify=$("#verify").val();
          var code=$("#code").val();
          var password=$("#password").val();
          if(mobile.length!=11){
              layer.open({
                  content: '手机号错误',
              });
              return false;
          }
          if(password.length<6 || password.length>15 ){
              layer.open({
                  content: '密码必须在6-15位',
              });
              return false;
          }
          if(!res){
              layer.open({
                  content: '图形验证码错误',
              });
              return false;
          }
          var xieyi=document.getElementById("xieyi").checked;
          if(!xieyi){
              layer.open({
                  content: '请勾选服务协议',
              });
              return false;
          }

          $.ajax({
              url: "/api/quickLogin1",
              type: "post",
              data: {mobile:mobile,password:password,validate:verify,code:code},
              dataType: "json",
              success: function (rs) {
                  if(rs.code==0){
                      layer.open({
                        content: '注册成功',
                      });

                      window.location.href='/url.php'
                  }else{
                      layer.open({
                        time: 1000,
                        content: rs.msg,
                      });
                  }
              }
          });
      }
     </script>
     <script type="text/javascript">
    var countdown = 60; 
    function sendemail(){
       var mobile=$("#mobile").val();
       if(mobile==''){
          return false;
       }
        var obj = $("#yanZengMa");
        settime(obj);

       
        settime(obj);
        $.ajax({
            url: "/api/getVerify",
            type: "get",
            data: {mobile:mobile,type:1},
            dataType: "json",
            success: function (rs) {
                if(rs.code==1){
                     layer.open({
                        content: rs.msg,                                 
                      });
                      if(rs.msg=='该手机已经注册过！'){ setTimeout(function(){  window.location.href='/url/index' }, 1000); }
                }
            }
        });

    }
    function settime(obj) { //发送验证码倒计时
        if (countdown == 0) { 
            obj.attr('disabled',false); 
            //obj.removeattr("disabled"); 
            obj.text("获取验证码");
            countdown = 60; 
            return;
        } else { 
            obj.attr('disabled',true);
            obj.text("重新发送(" + countdown + ")");
            countdown--; 
        } 
    setTimeout(function() { 
        settime(obj) }
        ,1000) 
    }
     </script>
</html>
