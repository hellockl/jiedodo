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
                          渠道：
                        </span>
                        
                        <label class="rowlabel">
                            <select name='name'>
                                <option  id='0'>全部</option>
                                <?php 
                                $name=$this->input->get('name');
                                foreach($info as $v){
                                    if($name==$v->id){
                                        echo "<option selected value='".$v->id."'>".$v->name."</option>";
                                    }else{
                                        echo "<option  value='".$v->id."'>".$v->name."</option>";
                                    }
                                    
                                }
                                ?>
                            </select>
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
                              <th>时间</th>
                              <th>名称</th>
                              <th>UV</th>
                              <th>申请数</th>
                              <th>转化率</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php if(!empty($data)){
                                foreach($data as $v){ ?>
                                    <tr>
                                        <td><?php echo $v->time;?></td>
                                        <td><?php echo $v->name;?></td>
                                        <td><?php echo $v->uv;?></td>
                                        <td><?php echo $v->num;?></td>
                                        <td><?php echo $v->bi;?></td>
                                      
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
</script>
</html>