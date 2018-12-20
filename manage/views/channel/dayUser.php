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
    <script type="text/javascript" src="<?php echo(BASEURL)?>/js/index.js"></script>
    <script src="/manage/js/echarts-all.js"></script>
    <script type="text/javascript">
        var a =<?php echo $data->a;?>;
        var b =<?php echo $data->b;?>;
//        var a=['苹果','小米','华为','魅族','vivo','oppo'];
//        var b =[100 ,80,150,60,30,50];
    </script>

</head>
<body>
<?php include(APPPATH . 'views/menu.php'); ?><!--右侧rght-->
<div class="rghtDiv">
    <?php include(APPPATH . 'views/header.php'); ?>
    <form class="form-horizontal">
        <label for="firstname" class="col-sm-1 control-label">时间</label>
        <div class="col-sm-8">
            <?php $starttime=$this->input->get('starttime');$endtime=$this->input->get('endtime');?>
            <input size="16" type="text" id="datetimeStart"  name="starttime" readonly class="form-horizontal" value="<?php echo $starttime!=''?$starttime:date('Y-m-d',strtotime(date('y-m-d'))-9*24*60*60);?>" >-
            <input size="16" type="text" id="datetimeEnd" name="endtime" readonly class="form-horizontal" value="<?php echo $endtime!=''?$endtime:date('Y-m-d');?>">
            <input type="submit" class="form-horizontal" >

        </div>
    </form>
    <div id="main" style="height: 400px;"></div>
</div>
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
    var myCharts = echarts.init(document.getElementById("main"));
    var option = {
        //标题
        title : {
            text : '<?php echo $title;?>',
        },
        //鼠标放上去提示文字
        tooltip : {
            show : true
        },
        //横坐标
        xAxis : {
            type : "category",
            name : "日期",
            data :a
        },
        //纵坐标
        yAxis : {
            type : "value",
            name : "数值"
        },
        //数据
        series : [
            {
                //数据关联名字
                name : "注册",
                type : "line",
                data :b
            }
        ]

    };
    //图形实例化
    myCharts.setOption(option);
</script>
</body>
</html>