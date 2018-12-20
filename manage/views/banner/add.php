<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>后台管理系统</title>
    <link rel="stylesheet" type="text/css" href="<?php echo(BASEURL)?>/css/main.css">
    <link rel="stylesheet" href="<?php echo(BASEURL)?>/css/bootstrap.min.css">
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
    <form class="form-horizontal" role="form" action="/manage/banner/add" method="post" onsubmit="return yan()">
        <input type="hidden" name="id" id='id' value="<?php echo isset($data->id)?$data->id:'';?>" >
        <input type="hidden" name="image" id="uploadImg" value="<?php echo isset($data->img) ? $data->img: '';?>">
        <div class="form-group">
            <label for="firstname" class="col-sm-1 control-label">标题</label>
            <div class="col-sm-11">
                <input type="text" class="form-control" name="title" required  placeholder="请输入标题" value="<?php echo isset($data->title)?$data->title:''; ?>" >
            </div>
        </div>
        <?php  $type=$this->input->get('type');if($type==1){?>
            <div class="form-group">
                <label for="firstname" class="col-sm-1 control-label">广告位</label>
                <div class="col-sm-11">
                    <select class="form-control" name="type">
                        <option value="1" <?php echo isset($data->type) && $data->type==1?'selected':'';?>>引导页(750*1334)</option>
                        <option value="2" <?php echo isset($data->type) && $data->type==2?'selected':'';?>>首页(720*130)</option>
                    </select>
                </div>
            </div>
        <?php }?>
        <div class="form-group">
            <label for="firstname" class="col-sm-1 control-label">链接</label>
            <div class="col-sm-11">
                <input type="text" class="form-control" name="url" value="<?php echo isset($data->url)?$data->url:''; ?>"  placeholder="请输入链接">
            </div>
        </div>
        <div class="form-group">
            <label for="firstname" class="col-sm-1 control-label"><?php echo $type==0?'轮播图(1080*680)':'广告图';?></label>
            <div class="col-sm-11">
                <from enctype="multipart/form-data">
                    <div id="upload" class="az-upload">
                        <img src="<?php echo isset($data->img) && !empty($data->img) ? $data->img : '/manage/img/upload.png'; ?>">
                        <p>点击这里上传...</p>
                    </div>
                    <input type="file" id="uploadFile" accept=".jpeg,.gif,.png,.jpg" name="files[]" style="display: none;">
                </from>
            </div>
        </div>
        <div class="form-group" style="text-align: right;">
            <button type="submit" class="btn btn-success">提交</button>
            <button type="button" class="btn btn-default " onclick="parent.layer.closeAll();"> 关 闭 </button>
        </div>
        </form>
</div>
<script src="/manage/js/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="/manage/js/layer/layer.js"></script>
<script>

    function yan(){
        if($("#uploadImg").val()==''){
            layer.alert('请上传轮播图!');
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
                    $("#upload").html("<img src="+ _this.img +"><p>点击这里上传...</p>");
                    $("#uploadImg").val(_this.img);
                } else {
                    //showError(data.msg);
                }
                uploading = false;
            }
        });
    }
</script>
</body>
</html>