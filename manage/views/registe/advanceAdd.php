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
    <form class="form-horizontal" role="form" action="/manage/Registe/advanceAdd" method="post">
        <input type="hidden" name="id" id='id' value="<?php echo $this->input->get('id');?>" >
        <div class="form-group">
            <label for="firstname" class="col-sm-1 control-label">金额</label>
            <div class="col-sm-1">
                <input type="text" class="form-control" onkeyup="value=value.replace(/[^\d]/g,'')" name="money" required  placeholder="请输入金额" >
            </div>
        </div>
        <div class="form-group" style="text-align: right;">
            <button type="submit" class="btn btn-success">提交</button>
            <button type="button" class="btn btn-default " onclick="parent.layer.closeAll();"> 关 闭 </button>
        </div>
        </form>
</div>
<script src="/manage/js/jquery-2.1.1.min.js"></script>

</script>
</body>
</html>