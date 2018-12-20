<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>后台管理系统</title>
    <link rel="stylesheet" type="text/css" href="<?php echo(BASEURL)?>/css/main.css">
    <link rel="stylesheet" href="<?php echo(BASEURL)?>/css/bootstrap.min.css">
    <link href="/manage/css/bootstrap-switch.min.css" rel="stylesheet">
    <script type="text/javascript" src="/manage/js/layui/layui.js"></script>
    <script src="<?php echo(BASEURL)?>/js/jquery-2.1.1.min.js"></script>
    <script src="<?php echo(BASEURL)?>/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo(BASEURL)?>/js/index.js"></script>
    <style type="text/css">
        td,th{
            font-size: 14px;
        }
        .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th{
            border-top: none;
        }
        .table>thead>tr>th {
            border-bottom: none;}
    </style>
</head>
<body>
<div class="container">
    <h3></h3>
    <form class="form-horizontal" role="form" action="/manage/edition/save" method="post" <?php $type=$this->input->get('type'); echo isset($type)?'':'onsubmit="return yan()"'?>  >
        <input type="hidden" name="type" value="<?php echo !isset($type)?'1':'0'?>" >
        <input type="hidden" name="apk" id='uploadImg'  >
    
        <div class="form-group">
            <label for="firstname" class="col-sm-2 control-label"><?php echo isset($type)?'IOS':'Android'?>版本号</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="edition" required  placeholder="请输入版本号" >
            </div>
        </div>
        <div class="form-group">
            <label for="firstname" class="col-sm-2 control-label">描述</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="depict" required  placeholder="请输入描述">
            </div>
        </div>
        <div class="form-group">
                <label for="firstname" class="col-sm-2 control-label">强制更新</label>
                <div class="col-sm-10">
                    <input type="checkbox" name="status" value="1"  checked id="checkbox" >
                </div>
            </div>
        <?php if(isset($type)){?>
            <div class="form-group">
            <label for="firstname" class="col-sm-2 control-label">IOS下载地址</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="ios" required  placeholder="请输入IOS下载地址" >
            </div>
        </div>
        <?php }else{?>
            <div class="form-group">
                <label for="firstname" class="col-sm-2 control-label">Android安装包</label>
                <div class="col-sm-10">
                    <from enctype="multipart/form-data">
                        <div id="upload" class="az-upload">
                            <img src="<?php echo isset($data->img) && !empty($data->img) ? $data->img : '/manage/img/upload.png'; ?>">
                            <p>点击这里上传...</p>
                        </div>
                        <input type="file" id="uploadFile" accept=".apk" name="files[]" style="display: none;">
                    </from>
                </div>
            </div>
         <?php }?>
        <div class="form-group" style="text-align: right;">
            <button type="submit" class="btn btn-success">提交</button>
            <button type="button" class="btn btn-default " onclick="parent.layer.closeAll();"> 关 闭 </button>
        </div>
        </form>
</div>
<script src="/manage/js/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="/manage/js/layer/layer.js"></script>
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
 

    function yan(){
        if($("#uploadImg").val()==''){
            layer.alert('请上传安装包!');
            return false;
        }

    }

    document.querySelector('#upload').addEventListener('click', function(evt) {
        evt.stopPropagation();
        document.getElementById('uploadFile').click();
    }, false);

    $("#uploadFile").change(function () {
        uploadImg();
    });

    function uploadImg() {
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
                    $("#upload").html("<img src='/img/upload.png'><p>点击这里上传...</p>");
                    $("#uploadImg").val(_this.img);
                } else {
                    layer.alert( data.msg);
                }
                uploading = false;
            }
        });
    }
</script>
</body>
</html>