<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>后台管理系统</title>
<link rel="stylesheet" type="text/css" href="<?php echo(BASEURL)?>/css/index.css">
<link rel="stylesheet" type="text/css" href="<?php echo(BASEURL)?>/css/main.css">
<link rel="stylesheet" href="<?php echo(BASEURL)?>/css/bootstrap.min.css">
<script src="<?php echo(BASEURL)?>/js/jquery-2.1.1.min.js"></script>
<script src="<?php echo(BASEURL)?>/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo(BASEURL)?>/js/index.js"></script>
<script type="text/javascript" src="/manage/js/layer/layer.js"></script>
</head>
<body>
<div class="container">
    <h3></h3>
    <form class="form-horizontal" role="form" action="/manage/news/add" method="post" onsubmit="return yan()">
        <input type="hidden" name="id" value="<?php echo isset($data->id)?$data->id:'';?>" >
        <input type="hidden" name="image" id="uploadImg" value="<?php echo isset($data->image) ? $data->image: '';?>">
        <div class="form-group">
            <label for="firstname" class="col-sm-1 control-label">封面</label>
            <div class="col-sm-11">
                <from enctype="multipart/form-data" >
                    <div id="upload" class="az-upload" style="height: auto" >
                        <img     src="<?php echo isset($data->image) && !empty($data->image) ? $data->image : '/manage/img/upload.png'; ?>">
                        <p>点击这里上传...</p>
                    </div>
                    <input type="file" id="uploadFile" accept=".jpeg,.gif,.png,.jpg"  style="display: none;">
                </from>
            </div>
        </div>
        <div class="form-group">
            <label for="firstname" class="col-sm-1 control-label">标题</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" name="title" required  placeholder="请输入标题" value="<?php echo isset($data->title)?$data->title:''; ?>" >
            </div>
            <label for="firstname" class="col-sm-1 control-label">置顶</label>
            <div class="col-sm-2">
                <input type="checkbox" class="form-control"  name="hot" <?php echo isset($data->hot) && $data->hot==1?'checked':''; ?>  value="1" >
            </div>
        </div>
        <div class="form-group">
            <label for="firstname" class="col-sm-1 control-label">简介</label>
            <div class="col-sm-11">
                <textarea rows="5"  class="form-control" required id="introduce" name="introduce"><?php echo isset($data->introduce)?$data->introduce:''; ?></textarea>
            </div>
        </div>
        <?php $type=$this->input->get('type');if($type==1){?>
            <input type="hidden" name="type" value="4">
        <?php }else{?>
            <div class="form-group">
                <label for="firstname" class="col-sm-1 control-label">类型</label>
                <div class="col-sm-11">
    <!--                1-热门问题 2-新手指南 3-口子解析  4-信用卡-->
                    <select  class="form-control" name="type">
                        <option <?php echo isset($data->type)&& $data->type==1?'selected':'';?> value="1">热门问题</option>
                        <option <?php echo isset($data->type)&& $data->type==2?'selected':'';?> value="2">新手指南</option>
                        <option <?php echo isset($data->type)&& $data->type==3?'selected':'';?> value="3">口子解析</option>
    <!--                    <option value="4">信用卡</option>-->
                    </select>
                </div>
            </div>
        <?php }?>
        <div class="form-group">
            <label for="firstname" class="col-sm-1 control-label">URL</label>
            <div class="col-sm-11">
                <input type="text" class="form-control" name="url" id="url"  placeholder="请输入URL" value="<?php echo isset($data->url)?$data->url:''; ?>" >
            </div>
        </div>

        <div class="form-group">
            <label for="firstname" class="col-sm-1 control-label">内容</label>
            <div class="col-sm-11">
                <textarea name="content" id="addeditor_id" style="width:100%;height:400px; margin:0 auto; visibility:hidden;"><?php echo isset($data->content)?$data->content:'';?></textarea>
            </div>
        </div>
        <div class="form-group" style="float: right;margin-top: 30px;">
            <button type="button" class="btn btn-default " onclick="parent.layer.closeAll();"> 关 闭 </button>
            <button type="submit" class="btn btn-success" > 保 存 </button>
        </div>
    </form>
</div>
<script src="/keditor/kindeditor.js"></script>
<script>

    $(document).ready(function () {
        KindEditor.ready(function(K) {
            var editor = K.create('textarea[name="content"]', {
                cssPath : '/keditor/plugins/code/prettify.css',
                uploadJson : '/keditor/php/upload_json.php',
                fileManagerJson : '/keditor/php/file_manager_json.php',
                allowFileManager : true,
                filterMode : false,
                afterCreate : function() {
                    var self = this;
                    K.ctrl(document, 13, function() {
                        self.sync();
                        K('form[name=example]')[0].submit();
                    });
                    K.ctrl(self.edit.doc, 13, function() {
                        self.sync();
                        K('form[name=example]')[0].submit();
                    });
                },
                afterBlur: function(){this.sync();}
            });
        });
    });
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
                    $("#upload").html("<img src="+ _this.img +"><p>点击这里上传...</p>");
                    $("#uploadImg").val(_this.img);
                } else {
                    //showError(data.msg);
                }
                uploading = false;
            }
        });
    }
    function yan(){
        if($("#uploadImg").val()==''){
            layer.alert('请上传封面!');
            return false;
        }

        if($("#url").val()=='' && $("#addeditor_id").val()==''){
            layer.alert('请输入URL或内容!');
            return false;
        }
    }
</script>
</body>
</html>