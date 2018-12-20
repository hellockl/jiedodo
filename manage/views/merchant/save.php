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
    <form  class="form-horizontal" action="/manage/merchant/add" method="post" target="iframe">
        <iframe name="iframe" frameborder=0 width=0 height=0></iframe>
         <input type="hidden" name="id" value="<?php echo $id=isset($data->id)?$data->id:'';?>" >
        <div class="form-group">
           <label for="firstname" class="col-sm-1 control-label">名称</label>
            <div class="col-sm-11">
                <input type="text" name="name"  class="form-control" value="<?php echo isset($data->name)?$data->name:''; ?>" required >
            </div>
        </div>

        <div class="form-group">
              <label for="firstname" class="col-sm-1 control-label">账户</label>
            <div class="col-sm-11">
                <input type="text" name="username" class="form-control"  value="<?php echo $username=isset($data->username)?$data->username:''; ?>" <?php if($username!=''){echo 'disabled';}?>  required >
            </div>
        </div>
       
        <div class="form-group">
            <label for="firstname" class="col-sm-1 control-label">密码</label>
            <div class="col-sm-11">
                <input type="password" class="form-control" minlength="6" maxlength="15" name="password"  <?php if(!isset($data->username)){echo 'required'; } ?> >
            </div>
        </div>
        <div class="form-group">

            <label for="firstname" class="col-sm-1 control-label"></label>
            <div class="col-sm-11">
                <?php if(isset($data->username)){echo '若不修改密码，请勿填写,密码长度为6-15位'; }else{echo '密码长度为6-15位';} ?> 
            </div>
           
        </div>

        
        <div class="form-group" style="float: right;margin-top: 30px;">
            <button type="button" class="btn btn-default " onclick="parent.layer.closeAll();"> 关 闭 </button>
            <button type="submit" class="btn btn-success" > 保 存 </button>
        </div>
    </form>
</div>
</body>
</html>