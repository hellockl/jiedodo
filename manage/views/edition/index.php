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
                          版本号
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
                              <th>版本号</th>
                              <th>描述</th>
                              <th>路径</th>
                              <th>状态</th>
                              <th>操作</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php if(!empty($data)){
                                foreach($data as $v){ ?>
                                    <tr>
                                        <td><?php echo $v->edition;?></td>
                                        <td><?php echo $v->depict;?></td>
                                        <td><?php echo !isset($ios)?'http://'.$_SERVER['HTTP_HOST']:''; echo $v->url;?></td>
                                        <td><?php if($v->status==1){ echo '强制更新';}else{ echo '不强制更新';} ?></td>
                                        <td>
                                            <button type="button" class="btn btn-default del" id="<?php echo $v->id;?>" >删除</button>
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
    $('.add').on('click', function(){
        layer.closeAll();
        var id=$(this).attr('id');
        var type=<?php echo $this->router->class=='adv'?1:0;?>;
        layer.open({
            type: 2 //此处以iframe举例
            ,title:id==undefined? '新增':'修改'
            ,area:  ['1160px','500px']
            ,shade: 0.6
            ,maxmin: false
            ,content:"<?php echo BASEURL;?>/edition/add<?php echo isset($ios)?'?type=1':''?>"
        });
    });
    $('.del').on('click', function(){
        layer.closeAll();
        var id=$(this).attr('id');
        layer.open({
            type: 1,
            content: '<br/>是否要删除！',
            btn: ['是','否'],
            yes:function () {
                $.ajax({
                    url: "/manage/edition/del",
                    type: "get",
                    data: {id:id},
                    dataType: "json",
                    success: function (rs) {
                        window.location.reload();
                    }
                });
            }
        });
    });
</script>
</html>