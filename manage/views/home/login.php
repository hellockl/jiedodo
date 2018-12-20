<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<title>登录 - 后台管理</title>
	<link href="<?php echo(BASEURL)?>/css/bootstrap.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo(BASEURL)?>/css/font-awesome.min.css" rel="stylesheet" type="text/css">
	<link href="<?php echo(BASEURL)?>/css/base-admin-3.css" rel="stylesheet" type="text/css">
	<link href="<?php echo(BASEURL)?>/css/base-admin-3-responsive.css" rel="stylesheet" type="text/css">
	<link href="<?php echo(BASEURL)?>/css/pages/signin.css" rel="stylesheet" type="text/css">
	<link href="<?php echo(BASEURL)?>/css/main.css" rel="stylesheet" type="text/css">
	<link href="<?php echo(BASEURL)?>/css/animate.min.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="<?php echo(BASEURL)?>/css/layui.css">
	<script src="/js/jquery.js"></script>
	<script src="<?php echo(BASEURL)?>/js/libs/bootstrap.js"></script>
</head>
<style type="text/css">
    body{ overflow: hidden;}
	.leftImg{
		width: 700px;
		height: 400px;
		position: absolute;
		top: 20%;
		left: 10%;
		z-index: -1;
		
	}
	.Imghou5{
		position: absolute;
		bottom:0px;
		left:7px;
		 animation:bounceInLeft 4s linear;
		-webkit-animation:bounceInLeft 4s linear; 
	}
	.Imghou1{
		position: absolute;
		top:50px;
		right:220px;
		animation:slideInLeft 2s linear;
		-webkit-animation:slideInLeft 2s linear; 
	}
	.Imghou7{
		position: absolute;
		top:50px;
		left:100px;
		animation:flipInX 2s linear;
		-webkit-animation:flipInX 2s linear; 
	}
	.Imghou6{
		position: absolute;
		top: 345px;
        left: 146px;
        animation:bounceInUp 4s linear;
		-webkit-animation:bounceInUp 4s linear; 
	}
	.Imghou4{
		position: absolute;
		top:190px;
		left:270px;
		animation:lightSpeedIn 2s linear;
		-webkit-animation:lightSpeedIn 2s linear; 
	}
	.loginDiv{
		width: 400px;
		height:350px;
		background: #FFFFFF;
		float: right;
		margin-top: 15%;
		margin-right:200px;
		border-radius: 6px;
		animation:bounceInDown 2s linear;
		-webkit-animation:bounceInDown 2s linear; /*Safari and Chrome*/
		z-index: 999999;
	}
	.logotop{
		width: 100%;
		text-align: center;
		padding-top: 30px;
		padding-bottom: 10px;
	}
	.logolist{
		width: 85%;
		height: 50px;
		border-bottom: #e3e6ec solid 1px;
		margin: 0 auto;
		margin-top: 10px;
		overflow: hidden;
	}
	.logolist em{
		width: 17px;
		height: 20px;
		display: block;
		float: left;
		margin-top: 15px;
	}
	.logolist span{
		width: 50px;
		height: 20px;
		display: block;
		float: left;
		margin-top:20px;
		font-size: 14px;
		line-height: 20px;
		text-align: center;
		color: #bfc6d4;
	}
	.logolist input{
		width:250px;
		height:30px;
		display: block;
		float: left;
        border: #ffffff solid 1px;
        margin-top: 15px;
        outline:none;
	}
	.logobutt{
		width: 85%;
		height: 50px;
		margin: 0 auto;
		margin-top:30px;
	}
	.logobutt button{
		width: 100%;
		height: 45px;
		display: block;
		margin: 0 auto;
		border-radius: 20px;
		background: #2e3548;
		color: #ffffff;
		border: none;
		outline:none;
	}
	.logofoot{
		width:95px;
		height: 30px;
		margin: 0 auto;
		margin-top: 10px;
		font-size: 14px;
		line-height: 30px;
	}
	.logofoot input{
		width: 20px;
		height: 20px;
		float: left;
		margin-right: 7px;
	}
</style>
<body onkeydown="keyLogin()">
	<div class="leftImg">
		<img src="/manage/img/nav/hou3.png" width="150" class="Imghou1">
		<img src="/manage/img/nav/hou5.png" width="180" class="Imghou5">
		<img src="/manage/img/nav/hou7.png" width="300" class="Imghou7">
		<img src="/manage/img/nav/hou4.png" width="200" class="Imghou4">
		<img src="/manage/img/nav/hou6.png" width="150" class="Imghou6">
	</div>
	<div class="loginDiv">
		<input type="password" id="asd" style="visibility: hidden;"/>
		<!--<form action="" method="post">-->
		<div class="logotop"><img width='42px' height='42px' src="<?php echo $logo;?>"></div>
		<div class="logolist">
			<em><img src="/manage/img/nav/hou1.png"></em>
			<span>账号</span>
			<input type="text" id="username" name="username" value="<?php echo($username)?>" placeholder="登录帐号">
		</div>
		<div class="logolist">
			<em><img src="/manage/img/nav/hou2.png"></em>
			<span>密码</span>
			<input type="password" id="password" name="password" value="<?php echo($password)?>" placeholder="登录密码" maxlength="30" onclick="checkLength();">
		</div>
		<div class="logobutt">
			<button id="logobutt">登录</button>
		</div>
		<!-- <div class="logofoot">
			<input type="checkbox" id="Field" name="signed" value="First Choice" tabindex="4"> 忘记密码
		</div> -->
<!--		</form>-->
	</div>
	<!--弹框-->
	<div id="activation" style="display: none; width: 90%; margin:0 auto;">
		<div class="row listTask">
                <div class="col-md-12" style="padding-top: 15px;">
                    <div class="input-group" style="width: 100%;">
                            <span class="input-group-btn">
                                <div class="btn btn-default">手机号：</div>
                            </span>
                            <input type="text" name="phone" id="phone" class="form-control" value="">
                    </div>
                </div>

                <div class="col-md-12" style="padding-top: 15px;">
                    <div class="input-group">
					<input type="text" class="form-control" id="verify">
					<span class="input-group-btn">
						<button class="btn btn-default" type="button" id="yanZengMa" onclick="sendemail()">
							获取验证码
						</button>
					</span>
				</div>
                </div>
        </div>
	</div>
</body>
<script src="<?php echo(BASEURL)?>/js/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="<?php echo(BASEURL)?>/js/index.js"></script>
<script type="text/javascript" src="<?php echo(BASEURL)?>/js/layer/layer.js"></script>
<script>

	$('#logobutt').on('click', function(e){
		var username=$("#username").val();
		var password=$("#password").val();
		if(username == ''){
		  $("#username").click(function(){
            $(".logolist").eq(0).css("border-bottom","#e3e6ec solid 1px");
          });
          $(".logolist").eq(0).css(
            "border-bottom","#ea1212 solid 1px"
          );
          $("#username").focus();
          $("#username").keydown(function(){
             $(".logolist").eq(0).css("border-bottom","#e3e6ec solid 1px");
          });
          return false;
		}
		if(password == ''){
		  $("#password").click(function(){
            $(".logolist").eq(1).css("border-bottom","#e3e6ec solid 1px");
          });
          $(".logolist").eq(1).css(
            "border-bottom","#ea1212 solid 1px"
          );
          $("#password").focus();
          $("#password").keydown(function(){
             $(".logolist").eq(1).css("border-bottom","#e3e6ec solid 1px");
          });
          return false;
		}
		if(password.length > 30){
			$("#numberList2").keydown(function(){
		       if(!reg.test($("#numberList2").val())){
		        $("#numberList2").val($("#numberList2").val().substring(0,$("#numberList2").val().length - 1));
		       }
		     })
			var school = layer.alert('密码过于长!',{
				skin:'layer-ext-moon'
			});
			return false;
		}
		$.ajax({
			url:'/manage/home/sign',
			type:'POST',
			dataType:'json',
			async:false,    //或false,是否异步
			data:{username:username,password:password },
			success:function(json){
//				output_response(1,['code'=>1,'str'=>'账号不能为空!']);
//				output_response(1,['code'=>2,'str'=>'密码不能为空!']);
//				output_response(1,['code'=>3,'str'=>'账号/密码错误,请重新登陆!']);
//				output_response(1,['code'=>4,'str'=>'请激活账户!']);
				if(json.err_code==0){
					window.location.replace('/manage/home/index');
					
				}else if(json.data.code==4){
					//alert('请激活账户');
					var activation = layer.open({
		              type: 1,
		              title: '请先激活账号',
		              area : ['400px' , '220px'],
		              content: $("#activation"),
		              btn: ['激活','取消'],
		              yes:function () {
                         var phone = $("#phone").val();
							if(phone && /^1[3|4|5|7|8|9]\d{9}$/.test(phone)){
								  
							} else{
							    $("#phone").click(function(){
					                $("#phone").css("border","#cccccc solid 1px");
					              });
					              $("#phone").css(
					                "border","#ea1212 solid 1px"
					              );
					              $("#phone").focus();
					              $("#phone").keydown(function(){
					                 $("#phone").css("border","#cccccc solid 1px");
					              });
					              return false;
							}
							$.ajax({
					            url: '/manage/home/activation',
					            type: 'post',
					            datatype: 'json',
					            data:{
					               username:$("#username").val(),
					               password:$("#password").val(),
					               mobile:$("#phone").val(),
					               verify:$("#verify").val()
					            },
					            success: function (e) {
					               var tmp = JSON.parse(e);
					               if(tmp.err_code == 4){
					               	  return false
					                 }
					               if(tmp.err_code == 1){
					               	 var school = layer.alert('验证码错误!',{                
						                skin:'layer-ext-moon'
						              }); 
					                 }
					               if(tmp.err_code == 0){
					               	layer.close(activation);
					               	var school = layer.alert('激活成功请重新登陆!',{                
						                skin:'layer-ext-moon',
						                btn: ['确定'],
							            yes:function () {
							              layer.close(school);
							              window.location.reload();
							            }
						              }); 
					                }
					               }
					          })
		                     
		               },
		               no:function(){
		                 layer.close(activation);
		               }
		            });
				}else{
					 var school = layer.alert(json.data.str,{                
						skin:'layer-ext-moon'
					}); 
				}
			}
		})
	});     	 
		var countdown=60; 
		function sendemail(){
		    var obj = $("#yanZengMa");
		    settime(obj);
		           var phone = $("#phone").val();
					if(phone && /^1[3|4|5|7|8|9]\d{9}$/.test(phone)){
						  
					} else{
					    $("#phone").click(function(){
			                $("#phone").css("border","#cccccc solid 1px");
			              });
			              $("#phone").css(
			                "border","#ea1212 solid 1px"
			              );
			              $("#phone").focus();
			              $("#phone").keydown(function(){
			                 $("#phone").css("border","#cccccc solid 1px");
			              });
			              return false;
					}
			       	$.ajax({
			            url: '/manage/home/getVerify',
			            type: 'post',
			            datatype: 'json',
			            data:{
			               mobile:$("#phone").val()
			            },
			            success: function (e) {
			               var tmp = JSON.parse(e);
			               if(tmp.err_code == 1){
		               	    var school = layer.alert(tmp.msg,{                
			                skin:'layer-ext-moon'
			               }); 
		                  }
			            }
			          })  
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
		$(document).keydown(function (event) {
        if (event.keyCode == 13) {//回车键对应code值为13
            $("#logobutt").click();//类选择器选择按钮
        }
       })
</script>
</html>