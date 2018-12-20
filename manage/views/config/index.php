<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<title>后台管理系统</title>
<link rel="stylesheet" type="text/css" href="<?php echo(BASEURL)?>/css/index.css">
<link rel="stylesheet" href="<?php echo(BASEURL)?>/css/bootstrap.min.css">
<link href="/manage/css/bootstrap-switch.min.css" rel="stylesheet">
<script src="/manage/js/jquery-2.1.1.min.js"></script>
</head>
<body>
<?php include(APPPATH . 'views/menu.php'); ?>
<!--右侧rght-->
<div class="rghtDiv">
    <?php include(APPPATH . 'views/header.php'); ?>
    <div class="container">
        <h3>公众信息</h3>
        <form class="form-horizontal" role="form" action="/manage/config/save" method="post" onsubmit='return  yan()' target="iframe">
            <iframe name="iframe" frameborder=0 width=0 height=0></iframe>
            <input type="hidden" name="image" id="uploadImg" value="">
            <input type="hidden" name="image1" id="uploadImg1" value="">
            <input type="hidden" name="image2" id="uploadImg2" value="">
            <input type="hidden" name="image3" id="uploadImg3" value="">
            <input type="hidden" name="image4" id="uploadImg4" value="">
            <input type="hidden" name="image5" id="uploadImg11" value="">
            <div class="form-group">
                <label for="firstname" class="col-sm-1 control-label">名称</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="publicTitle"   placeholder="请输入名称，不填写则隐藏公众号信息" value="<?php echo isset($data->publicTitle)?$data->publicTitle:'';?>" >
                </div>
                <div class="col-sm-1" style="text-align: right;">
                    <button type="submit" class="btn btn-success">保存</button>
                </div>
            </div>

            <div class="form-group">
                <label for="firstname" class="col-sm-1 control-label">logo</label>
                <div class="col-sm-5">
                    <from enctype="multipart/form-data">
                        <div id="upload" class="az-upload">
                            <img style='width:111px;' src="<?php echo isset($data->publicLogo) && !empty($data->publicLogo) ? $data->publicLogo : '/manage/img/upload.png'; ?>">
                            <p>点击这里上传...</p>
                        </div>
                        <input type="file" id="uploadFile" accept=".jpeg,.gif,.png,.jpg" name="files[]" style="display: none;">
                    </from>
                </div>
                <label for="firstname" class="col-sm-2 control-label">二维码</label>
                <div class="col-sm-4">
                    <from enctype="multipart/form-data">
                        <div id="upload1" class="az-upload">
                            <img style='width:222px;' src="<?php echo isset($data->publicImg) && !empty($data->publicImg) ? $data->publicImg : '/manage/img/upload.png'; ?>">
                            <p>点击这里上传...</p>
                        </div>
                        <input type="file" id="uploadFile1" accept=".jpeg,.gif,.png,.jpg" name="files[]" style="display: none;">
                    </from>
                </div>
            </div>

            <div class="form-group">
                <label for="firstname" class="col-sm-1 control-label">客服电话</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" name="serviceMobile" required  placeholder="请输入电话" value="<?php echo isset($data->serviceMobile)?$data->serviceMobile:'';?>" >
                </div>
                <label for="firstname" class="col-sm-2 control-label">反馈二维码</label>
                <div class="col-sm-4">
                    <from enctype="multipart/form-data">
                        <div id="upload11" class="az-upload">
                            <img style='width:222px;' src="<?php echo isset($data->feedback) && !empty($data->feedback) ? $data->feedback : '/manage/img/upload.png'; ?>">
                            <p>点击这里上传...</p>
                        </div>
                        <input type="file" id="uploadFile11" accept=".jpeg,.gif,.png,.jpg" name="files[]" style="display: none;">
                    </from>
                </div>
            </div>

            <h3>关于平台</h3>
            <div class="form-group">
                <label for="firstname" class="col-sm-1 control-label">logo</label>
                <div class="col-sm-11">
                    <from enctype="multipart/form-data">
                        <div id="upload2" class="az-upload">
                            <img style='width:111px;' src="<?php echo isset($data->logo) && !empty($data->logo) ? $data->logo : '/manage/img/upload.png'; ?>">
                            <p>点击这里上传...</p>
                        </div>
                        <input type="file" id="uploadFile2" accept=".jpeg,.gif,.png,.jpg" name="files[]" style="display: none;">
                    </from>
                </div>
            </div>
            <div class="form-group">
                <label for="firstname" class="col-sm-1 control-label">ios二维码</label>
                <div class="col-sm-5">
                    <from enctype="multipart/form-data">
                        <div id="upload3" class="az-upload">
                            <img style='width:222px;' src="<?php echo isset($data->image) && !empty($data->image) ? $data->image : '/manage/img/upload.png'; ?>">
                            <p>点击这里上传...</p>
                        </div>
                        <input type="file" id="uploadFile3" accept=".jpeg,.gif,.png,.jpg" name="files[]" style="display: none;">
                    </from>
                </div>
                <label for="firstname" class="col-sm-2 control-label">安卓二维码</label>
                <div class="col-sm-4">
                    <from enctype="multipart/form-data">
                        <div id="upload4" class="az-upload">
                            <img style='width:222px;' src="<?php echo isset($data->androidImage) && !empty($data->androidImage) ? $data->androidImage: '/manage/img/upload.png'; ?>">
                            <p>点击这里上传...</p>
                        </div>
                        <input type="file" id="uploadFile4" accept=".jpeg,.gif,.png,.jpg" name="files[]" style="display: none;">
                    </from>
                </div>
            </div>
            <h3>注册图形验证码</h3>
            <div class="form-group">
                <label for="firstname" class="col-sm-1 control-label"></label>
                <div class="col-sm-5">
                    <input type="checkbox" name="sms" value="1"  <?php echo isset($data->sms) && $data->sms==1 ? 'checked' : ''; ?> id="checkbox" >
                </div>
            </div>
            <h3>修改密码</h3>
            <div class="form-group">
                <label for="firstname" class="col-sm-1 control-label"></label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id='password' name="password" placeholder='若不修改密码，请勿填写,密码长度为6-15位 ' >
                </div>
            </div>
            <h3>分享</h3>
            <div class="form-group">
                <label for="firstname" class="col-sm-1 control-label">分享标题</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="title" required value="<?php echo isset($data->title)?$data->title:'';?>" >
                </div>
            </div>
            <div class="form-group">
                <label for="firstname" class="col-sm-1 control-label">分享内容</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="content" required value="<?php echo isset($data->content)?$data->content:'';?>" >
                </div>
            </div>
            <div class="form-group">
                <label for="firstname" class="col-sm-1 control-label">ios下载链接</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="iosDownload" required value="<?php echo isset($data->iosDownload)?$data->iosDownload:'';?>" >
                </div>
            </div>
            <div class="form-group">
                <label for="firstname" class="col-sm-1 control-label">安卓下载链接</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="androidDownload" required value="<?php echo isset($data->androidDownload)?$data->androidDownload:'';?>" >
                </div>
            </div>
           
        </form>
    </div>

</div>
</body>
<script src="/manage/js/bootstrap-switch.min.js"></script>
<script type="text/javascript">
    $(function(){
        /* 初始化控件 */
        $("#checkbox").bootstrapSwitch({
            onText : "ON",      // 设置ON文本
            offText : "OFF",    // 设置OFF文本
            onColor : "success",// 设置ON文本颜色     (info/success/warning/danger/primary)
            offColor : "danger",  // 设置OFF文本颜色        (info/success/warning/danger/primary)
            size : "normal",    // 设置控件大小,从小到大  (mini/small/normal/large)
            // 当开关状态改变时触发
            onSwitchChange : function(event, state) {
//                if (state == true) {
//                    alert("ON");
//                } else {
//                    alert("OFF");
//                }
            }
        });
    });

    document.querySelector('#upload').addEventListener('click', function(evt) {
        evt.stopPropagation();
        document.getElementById('uploadFile').click();
    }, false);
    $("#uploadFile").change(function () {
        //上传图片
        var _this = this;
        var formData =new FormData(document.getElementById('uploadFile')[0]);
        formData.append('file',$(':file')[0].files[0]);
        $.ajax({
            url: '/manage/home/uploadImg/',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType:"json",
            beforeSend: function(){
                uploading = true;
            },
            success : function(data) {
                if (data.err_code == 0) {
                    _this.img = data.data.imgPath;
                    $("#upload").html("<img style='width:111px;' src="+ _this.img +"><p>点击这里上传...</p>");
                    $("#uploadImg").val(_this.img);
                } else {
                    //showError(data.msg);
                }
                uploading = false;
            }
        });
    });
    document.querySelector('#upload1').addEventListener('click', function(evt) {
        evt.stopPropagation();
        document.getElementById('uploadFile1').click();
    }, false);
    $("#uploadFile1").change(function () {
        //上传图片
        var _this = this;
        var formData =new FormData(document.getElementById('uploadFile')[1]);
        formData.append('file',$(':file')[1].files[0]);
        $.ajax({
            url: '/manage/home/uploadImg/',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType:"json",
            beforeSend: function(){
                uploading = true;
            },
            success : function(data) {
                if (data.err_code == 0) {
                    _this.img = data.data.imgPath;
                    $("#upload1").html("<img style='width:222px;' src="+ _this.img +"><p>点击这里上传...</p>");
                    $("#uploadImg1").val(_this.img);
                } else {
                    //showError(data.msg);
                }
                uploading = false;
            }
        });
    });
    document.querySelector('#upload2').addEventListener('click', function(evt) {
        evt.stopPropagation();
        document.getElementById('uploadFile2').click();
    }, false);
    $("#uploadFile2").change(function () {
        //上传图片
        var _this = this;
        var formData =new FormData(document.getElementById('uploadFile')[3]);
        formData.append('file',$(':file')[3].files[0]);
        $.ajax({
            url: '/manage/home/uploadImg/',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType:"json",
            beforeSend: function(){
                uploading = true;
            },
            success : function(data) {
                if (data.err_code == 0) {
                    _this.img = data.data.imgPath;
                    $("#upload2").html("<img style='width:111px;' src="+ _this.img +"><p>点击这里上传...</p>");
                    $("#uploadImg2").val(_this.img);
                } else {
                    //showError(data.msg);
                }
                uploading = false;
            }
        });
    });
    document.querySelector('#upload3').addEventListener('click', function(evt) {
        evt.stopPropagation();
        document.getElementById('uploadFile3').click();
    }, false);
    $("#uploadFile3").change(function () {
        //上传图片
        var _this = this;
        var formData =new FormData(document.getElementById('uploadFile')[4]);
        formData.append('file',$(':file')[4].files[0]);
        $.ajax({
            url: '/manage/home/uploadImg/',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType:"json",
            beforeSend: function(){
                uploading = true;
            },
            success : function(data) {
                if (data.err_code == 0) {
                    _this.img = data.data.imgPath;
                    $("#upload3").html("<img style='width:222px;' src="+ _this.img +"><p>点击这里上传...</p>");
                    $("#uploadImg3").val(_this.img);
                } else {
                    //showError(data.msg);
                }
                uploading = false;
            }
        });
    });
    document.querySelector('#upload4').addEventListener('click', function(evt) {
        evt.stopPropagation();
        document.getElementById('uploadFile4').click();
    }, false);
    $("#uploadFile4").change(function () {
        //上传图片
        var _this = this;
        var formData =new FormData(document.getElementById('uploadFile')[5]);
        formData.append('file',$(':file')[5].files[0]);
        $.ajax({
            url: '/manage/home/uploadImg/',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType:"json",
            beforeSend: function(){
                uploading = true;
            },
            success : function(data) {
                if (data.err_code == 0) {
                    _this.img = data.data.imgPath;
                    $("#upload4").html("<img style='width:222px;' src="+ _this.img +"><p>点击这里上传...</p>");
                    $("#uploadImg4").val(_this.img);
                } else {
                    //showError(data.msg);
                }
                uploading = false;
            }
        });
    });
    document.querySelector('#upload11').addEventListener('click', function(evt) {
        evt.stopPropagation();
        document.getElementById('uploadFile11').click();
    }, false);
    $("#uploadFile11").change(function () {
        //上传图片
        var _this = this;
        var formData =new FormData(document.getElementById('uploadFile')[2]);
        formData.append('file',$(':file')[2].files[0]);
        $.ajax({
            url: '/manage/home/uploadImg/',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType:"json",
            beforeSend: function(){
                uploading = true;
            },
            success : function(data) {
                if (data.err_code == 0) {
                    _this.img = data.data.imgPath;
                    $("#upload11").html("<img style='width:222px;' src="+ _this.img +"><p>点击这里上传...</p>");
                    $("#uploadImg11").val(_this.img);
                } else {
                    //showError(data.msg);
                }
                uploading = false;
            }
        });
    });
    function yan(){
        var pass =$("#password").val();
        if(pass!='' && (pass.length<6 || pass.length>15)){
            alert('密码长度为6-15位');
            return false;
        }
       
    }

</script>
</html>