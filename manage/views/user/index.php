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
                          手机号：
                        </span>
                        <label class="rowlabel">
                            <input type="text" name="name"  value="<?php echo $this->input->get('name');?>">
                        </label>
                        <span class="rowspan">
                          渠道：
                        </span>
                        
                        <label class="rowlabel">
                            <select name='lai'>
                                <option value='0'>全部</option>
                                <?php 
                                $lai=$this->input->get('lai');
                                foreach($info as $v){
                                    if($lai==$v->id){
                                        echo "<option selected value='".$v->id."'>".$v->name."</option>";
                                    }else{
                                        echo "<option  value='".$v->id."'>".$v->name."</option>";
                                    }
                                    
                                }
                                ?>
                            </select>
                        </label>
                         <span class="rowspan">
                          时间：
                        </span>
                        <?php $starttime=$this->input->get('starttime');$endtime=$this->input->get('endtime');?>
                        <label class="rowlabel">
                            <input size="16" type="text" id="datetimeStart" placeholder="点击选择开始时间"  name="starttime" readonly class="form-horizontal" value="<?php echo $starttime!=''?$starttime:'';?>" >-
                        </label>
                        <label class="rowlabel">
                            <input size="16" type="text" id="datetimeEnd" placeholder="点击选择结束时间"  name="endtime" readonly class="form-horizontal" value="<?php echo $endtime!=''?$endtime:'';?>">
                        </label>
                        <button type="submit" class="btn btn-info">&nbsp;&nbsp;查 询&nbsp;&nbsp;</button>
                    </div>
                </div>
                </form>
                <div class="row" style="width: 95%; margin:0 auto; margin-top:20px;">
                    <div class="AccountNumberTaeb">
                        <table class="table table-bordered table-hover">
                          <thead>
                            <tr>
                              <th>渠道来源</th>
                              <th>昵称</th>
                              <th>手机号</th>
                              <th>当日分发系数</th>
                              <th>注册时间</th>
                              <th>操作</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php if(!empty($data)){
                                foreach($data as $v){ ?>
                                    <tr>
                                        <td><?php echo $v->lai;?></td>
                                        <td><?php echo $v->name;?></td>
                                        <td><?php echo substr_replace($v->mobile,'****',3,4); ?></td>
                                        <td><?php echo $v->fen; ?></td>
                                        <td><?php echo $v->createdTime;?></td>
                                        <td>
                                            <button type="button" class="btn btn-default fen" id="<?php echo $v->id;?>" >日分发系数</button>
                                            <button type="button" class="btn btn-default shenqing" id="<?php echo $v->id;?>" >申请记录</button>
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
<link href="/manage/datetime/bootstrap-datetimepicker.min.css" rel="stylesheet" />
<script src="/manage/datetime/bootstrap-datetimepicker.min.js"></script>
<script src="/manage/datetime/bootstrap-datetimepicker.zh-CN.js"></script>
<script type="text/javascript">

    $(function(){
        $("#datetimeStart").datetimepicker({
            format: 'yyyy-mm-dd', minView:'month', language: 'zh-CN', autoclose:true, startDate:'2018-10-01'
        }).on('hide', function(event) {
            event.preventDefault();
            event.stopPropagation();
            var endTime = event.date;
            $('#datetimeEnd').datetimepicker('setStartDate',endTime);
        });
        $("#datetimeEnd").datetimepicker({
            format: 'yyyy-mm-dd', minView:'month', language: 'zh-CN', autoclose:true, startDate:'2018-10-01'
        }).on('hide', function(event) {
            event.preventDefault();
            event.stopPropagation();
            var startTime = event.date;
            $('#datetimeStart').datetimepicker('setEndDate',startTime);
        });

    });

    $('.shenqing').on('click', function(){
        layer.closeAll();
        var id=$(this).attr('id');
        layer.open({
            type: 2 //此处以iframe举例
            ,title: '申请记录'
            ,area: ['800px', '600px']
            ,shade: 0.6
            ,maxmin: false
            ,content:"<?php echo BASEURL;?>/user/shenqing?id="+id
        });
    });
    $('.fen').on('click', function(){
        layer.closeAll();
        var id=$(this).attr('id');
        layer.open({
            type: 2 //此处以iframe举例
            ,title: '日分发系数'
            ,area: ['800px', '600px']
            ,shade: 0.6
            ,maxmin: false
            ,content:"<?php echo BASEURL;?>/user/fen?id="+id
        });
    });
</script>
</html>