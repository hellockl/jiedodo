<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<title>后台管理系统</title>
<link rel="stylesheet" type="text/css" href="<?php echo(BASEURL)?>/css/index.css">
<link rel="stylesheet" href="<?php echo(BASEURL)?>/css/bootstrap.min.css">
<script src="<?php echo(BASEURL)?>/js/jquery-2.1.1.min.js"></script>
<script src="<?php echo(BASEURL)?>/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo(BASEURL)?>/js/index.js"></script>
</head>
<style type="text/css">
    .layui-layer{ width: 250px; }
</style>
<body>
    <?php include(APPPATH . 'views/menu.php'); ?>
        <!--右侧rght-->
        <div class="rghtDiv">
            <?php include(APPPATH . 'views/header.php'); ?>
            <!--账号-->
            <div class="BoxList">
                <form action="" method="get">
                <div class="AccountNumber">
                    <b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;条件过滤</b>
                    <div class="row" style="width: 98%; margin:0 auto; margin-top:20px;">
                        <span class="rowspan">
                          姓名：
                        </span>
                        <label class="rowlabel">
                            <input type="text" name="name" value="<?php echo $this->input->get('name');?>">
                        </label>
                        <button type="submit" class="btn btn-info">&nbsp;&nbsp;查 询&nbsp;&nbsp;</button>
                        <button type="button" class="btn btn-success add">&nbsp;&nbsp;新 增&nbsp;&nbsp;</button>
                    </div>
                </div>
                </form>
                <div class="row" style="width: 95%; margin:0 auto; margin-top:20px;">
                    <div class="AccountNumberTaeb">
                        <table class="table table-bordered table-hover">
                          <thead>
                            <tr>
                              <th>名称</th>
                              <th>账户</th>
                              <th>链接</th>
                              <th>状态</th>
                              <th>操作</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php if(!empty($data)){
                                foreach($data as $v){ ?>
                                    <tr>
                                        <td><?php echo $v->name;?></td>
                                        <td><?php echo $v->username;?></td>
                                        <td><?php echo 'http://'.$_SERVER['HTTP_HOST'].'/'.$v->id;?></td>
                                        <td><?php if($v->status==0){echo '开启';}else{echo '禁用';}?></td>
                                        <td>
                                            <button type="button" class="btn btn-default xinxi" id="<?php echo $v->id;?>">注册信息</button>
                                            <button type="button" class="btn btn-default add" id="<?php echo $v->id;?>">编辑</button>
                                            <button type="button" name="<?php echo $v->status;?>" id="<?php echo $v->id;?>"  class="btn btn-<?php if($v->status==1){echo 'success shanchu">开启';}else{echo 'success shanchu" style="background-color: red; border-color: red;">禁用';}?> </button>
                                            <button type="button" class="btn btn-default del" id="<?php echo $v->id;?>">删除</button>
                                        </td>
                                    </tr>
                                <?php } }else{ ?>
                                <tr data-index="0" class="">
                                    <td colspan ="8">
                                        <center>暂无数据</center>
                                    </td>
                                </tr>
                            <?php }?>
                          </tbody>
                        </table>
                    </div>
                    <?php echo $pages?>
                </div>
            </div>
        </div>
</body>

<script>
    $('.shanchu').on('click', function(){
        layer.closeAll();
        var id=$(this).attr('id');
        var aid=$(this).attr('name');
        var tishi='开启';
        if(aid==0){
             tishi='禁用';
        }
        layer.open({
            type: 1,
            content: '<br/>是否要'+tishi+'！',
            btn: ['是','否'],
            yes:function () {
                $.ajax({
                    url: "<?php echo BASEURL;?>/merchant/merchantStatus",
                    type: "get",
                    data: {id:id,aid:aid},
                    dataType: "json",
                    success: function (rs) {
                        window.location.reload();
                    }
                });
            }
        });

    });

$('.add').on('click', function(){
    layer.closeAll();
    var id=$(this).attr('id');
    var type='新增';
    if(id!=undefined){
         type='修改';
    }
    layer.open({
        type: 2 //此处以iframe举例
        ,title: type
        ,area: ['800px', '700px']
        ,shade: 0
        ,maxmin: false
        ,content:"<?php echo BASEURL;?>/merchant/save?id="+id
    });
});
    $('.xinxi').on('click', function(){
        layer.closeAll();
        var id=$(this).attr('id');
        layer.open({
            type: 2 //此处以iframe举例
            ,title: '注册信息'
            ,area: ['50%', '80%']
            ,shade: 0
            ,maxmin: false
            ,content:"<?php echo BASEURL;?>/merchant/xinxi?id="+id
        });
    });
$('.del').on('click', function(){
    var id=$(this).attr('id');
     layer.open({
        type: 1,
        content: '<br/>是否要删除！',
        btn: ['是','否'],
        yes:function () {
             $.ajax({
                url: "<?php echo BASEURL;?>/Merchant/merchantDel",
                type: "get",
                data: {id:id},
                success: function (rs) {
                    window.location.reload();
                }
            });
        }
    });
   
});
</script>
</html>