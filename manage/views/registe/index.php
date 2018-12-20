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
                          名称：
                        </span>
                        <label class="rowlabel">
                            <input type="text" name="name" value="<?php echo $this->input->get('name');?>">
                        </label>
                        <button type="submit" class="btn btn-info">&nbsp;&nbsp;查 询&nbsp;&nbsp;</button>
                        <button type="button" class="btn btn-success UserBo" id="all"  data-method="setTop">&nbsp;&nbsp;新 增&nbsp;&nbsp;</button>
                    </div>
                </div>
                </form>
                <div class="row" style="width: 95%; margin:0 auto; margin-top:20px;">
                    <div class="AccountNumberTaeb">
                        <table class="table table-bordered table-hover">
                          <thead>
                            <tr>
                              <th>名称</th>
                              <th>商标</th>
                              <th>简介</th>
                              <th>借款额度</th>
                              <th>置顶</th>
                              <th>排序</th>
                              <th>被申请次数</th>
                              <th>状态</th>
                              <th>操作</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php if(!empty($data)){
                                foreach($data as $v){ ?>
                                    <tr>
                                        <td><?php echo $v->shopName;?></td>
                                        <td><img src="<?php echo $v->logo;?>" width="50px"/></td>
                                        <td><?php echo $v->abstract;?></td>
                                        <td><?php echo $v->minQuota.'-'.$v->maxQuota;?></td>
                                        <td><?php echo $v->hot==1?'置顶':'未置顶';?></td>
                                        <td><?php echo $v->sort;?></td>
                                        <td><?php echo $v->count;?></td>
                                        <td><?php echo $v->status==1?'关闭':'开启';?></td>
                                        <td>
                                            <button type="button" class="btn btn-default fen"  id="<?php echo $v->id;?>">申请统计</button>
                                            <button type="button" class="btn btn-default UserBo" data-type="0" id="<?php echo $v->id;?>" data-method="setTop">编辑</button>
                                            <?php if($v->status==1){  ?>
                                                <button type="button" class="btn btn-success UserBo" data-type="0" onclick='kai(<?php echo $v->id;?>,0)' >开启</button>
                                           <?php }else{ ?>
                                                <button type="button" class="btn btn-success UserBo"  onclick="kai(<?php echo $v->id;?>,1)" style="background-color: red; border-color: red;" >关闭</button>
                                            <?php  } ?>
                                            <button type="button" id="<?php echo $v->id;?>"  class="btn btn-warning shanchu">删除</button>
                                            <button type="button" class="btn btn-default yu" id="<?php echo $v->id;?>">预付金额</button>

                                        </td>
                                    </tr>
                                <?php } }else{ ?>
                                <tr data-index="0" class="">
                                    <td colspan ="10">
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
    <iframe name="chongzhi" frameborder=0 width=0 height=0></iframe>

</body>
<script>
layui.use('layer', function(){ //独立版的layer无需执行这一句
    var $ = layui.jquery, layer = layui.layer; //独立版的layer无需执行这一句
    //触发事件
    var active = {
        setTop: function(){
            var id =$(this).attr('id');
            var title ='修改';
            if(id=='all'){
                title ='新增';
            }
            //多窗口模式，层叠置顶
            layer.open({
                type: 2 //此处以iframe举例
                ,title: title
                ,area: ['1160px', '100%']
                ,shade: 0.6
                ,maxmin: false
                ,content:"<?php echo BASEURL;?>/registe/save?id="+id
            });
        },
    };
    $('.UserBo').on('click', function(){
        layer.closeAll();
        active[$(this).data('method')].call(this) ;
    });
});
$('.shanchu').on('click', function(){
    var id=$(this).attr('id');
     layer.open({
            type: 1,
            content: '<br/>是否要删除！',
            btn: ['是','否'],
            yes:function () {
                 $.ajax({
                    url: "<?php echo BASEURL;?>/registe/registeDel",
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
function kai(id,status){
     $.ajax({
        url: "<?php echo BASEURL;?>/registe/registeStatus",
        type: "get",
        data: {id:id,status:status},
        dataType: "json",
        success: function (rs) {
            window.location.reload();
        }
    });

}   
$('.fen').on('click', function(){
    layer.closeAll();
    var id=$(this).attr('id');
    layer.open({
        type: 2 //此处以iframe举例
        ,title: '申请统计'
        ,area: ['800px', '600px']
        ,shade: 0.6
        ,maxmin: false
        ,content:"<?php echo BASEURL;?>/registe/tongji?id="+id
    });
});
$('.yu').on('click', function(){
    layer.closeAll();
    var id=$(this).attr('id');
    layer.open({
        type: 2 //此处以iframe举例
        ,title: '预付金额'
        ,area: ['800px', '600px']
        ,shade: 0.6
        ,maxmin: false
        ,content:"<?php echo BASEURL;?>/registe/advance?id="+id
    });
});
</script>
</html>