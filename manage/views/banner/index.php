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
                          标题：
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
                              <th>标题</th>
                              <th>封面</th>
                              <th>链接</th>
                                <?php echo $this->router->class=='adv'?'<th>广告位</th>':'';?>
                              <th>状态</th>
                              <th>操作</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php if(!empty($data)){
                                foreach($data as $v){ ?>
                                    <tr>
                                        <td><?php echo $v->title;?></td>
                                        <td><img src="<?php echo $v->img;?>" width="100px"/></td>
                                        <td><?php echo $v->url;?></td>
                                        <?php if($this->router->class=='adv'){
                                            $v->type=$v->type==1?'引导页':'首页';
                                            echo '<td>'.$v->type.'</td>';
                                        }?>
                                        <td><?php if($v->status==0){ echo '启用';}else{ echo '禁用';} ?></td>
                                        <td>
                                            <button type="button" class="btn btn-default add" id="<?php echo $v->id;?>" >编辑</button>
                                            <?php if($v->status==0){ ?>
                                                <button type="button" style="background-color: red; border-color: red;" class="btn btn-default" onclick="bannerStatus(<?php echo $v->id;?>,1)" >禁用</button>
                                            <?php }else{ ?>
                                                <button type="button" class="btn btn-success" onclick="bannerStatus(<?php echo $v->id;?>,0)" >开启</button>
                                            <?php } ?>
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
            ,area:  ['1160px',type==1?'450px': '400px']
            ,shade: 0.6
            ,maxmin: false
            ,content:"<?php echo BASEURL;?>/banner/add?type="+type+"&id="+id
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
                    url: "/manage/banner/bannerDel",
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
    function bannerStatus(id,status){
        $.ajax({
            url: "/manage/banner/bannerStatus" ,
            type: "POST",
            data: {id:id,status:status},
            success: function (data) {
                var tmp = JSON.parse(data);
                if(tmp.err_code==0){
                    window.location.reload(true);
                }else{
                    alert(tmp.msg);
                }
            }
        });
    }
</script>
</html>