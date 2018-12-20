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
    <script src="/manage/js/echarts-all.js"></script>


    <script type="text/javascript">
        var a =<?php echo $data->a;?>;
        var b =<?php echo $data->b;?>;
        var c =<?php echo $data->c;?>;
        // a=['苹果','小米','华为','魅族','vivo','oppo','应用宝'];
        // b =[100 ,80,150,60,30,50,120];
        // c =[80,30,50,10,100,40,60];
    </script>
    <style>
        .container-fluid {
            margin: 0 20px;
            height: 350px;
        }
        .container-fluid .wrap {
            display: flex;
            flex-wrap: wrap;
            margin-top:20px;
        }
        .container-fluid .box {
            flex: none;
            width: 11%;
            height: 150px;
            margin-left: 1%;
            margin-bottom:10px;
            border-radius: 10px;
            box-shadow: 0 2px 10px 0 #aaa;
        }
        .container-fluid .box{
            font-size:1.2em;
            text-align:center;
        }
        .hang{
            margin-top:30px;
            color: #fff;
        }
        h2{
            line-height:1;
        }

    </style>
</head>
<body>
<?php include(APPPATH . 'views/menu.php'); ?><!--右侧rght-->
<div class="rghtDiv">
    <?php include(APPPATH . 'views/header.php'); ?>

    <div class="container-fluid">
        <div class="row wrap">
            <div class="box" style="background-color: #da7272" onclick="dian(1)">
                <div class="hang">
                    <p>总注册数</p>
                    <h2><?php echo $data->zhuce;?></h2>
                </div>
            </div>
            <div class="box" style="background-color: #da7272" onclick="dian(1)">
                <div class="hang">
                    <p>日新增用户数</p>
                    <h2><?php echo $data->count1;?></h2>
                </div>
            </div>
            <div class="box" style="background-color: #72bac5" onclick="dian(2)">
                <div class="hang">
                    <p>月新增用户数</p>
                    <h2><?php echo $data->count2;?></h2>
                </div>
            </div>
            <div class="box" style="background-color: #d7c082" onclick="dian(3)">
                <div class="hang">
                    <p>日申请次数</p>
                    <h2><?php echo $data->count3;?></h2>
                </div>
            </div>
            <div class="box" style="background-color: #af82bd" onclick="dian(4)">
                <div class="hang">
                    <p>日申请用户</p>
                    <h2><?php echo $data->count4;?></h2>
                </div>
            </div>
            <div class="box" style="background-color: #81bf7c" onclick="dian(5)">
                <div class="hang">
                    <p>月申请次数</p>
                    <h2><?php echo $data->count5;?></h2>
                </div>
            </div>
            <div class="box" style="background-color: #918ccc" onclick="dian(6)">
                <div class="hang">
                    <p>月申请用户</p>
                    <h2><?php echo $data->count6;?></h2>
                </div>
            </div>
            <div class="box" style="background-color: #bc7da1" onclick="dian(7)">
                <div class="hang">
                    <p>日分系数</p>
                    <h2><?php echo $data->count7;?></h2>
                </div>
            </div>
        </div>
    </div>
    <form class="form-horizontal">
        <div class="form-group">
            <label for="firstname" class="col-sm-1 control-label">时间</label>
            <div class="col-sm-11">
                <?php $starttime=$this->input->get('starttime');$endtime=$this->input->get('endtime');?>
                <input size="16" type="text" id="datetimeStart"  name="starttime" readonly class="form-horizontal" value="<?php echo $starttime!=''?$starttime:date('Y-m-d');?>" >-
                <input size="16" type="text" id="datetimeEnd" name="endtime" readonly class="form-horizontal" value="<?php echo $endtime!=''?$endtime:date('Y-m-d');?>">
                <input type="submit" class="form-horizontal" >

            </div>
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
            text : "渠道统计",
        },
        //鼠标放上去提示文字
        tooltip : {
            show : true
        },
        //横坐标
        xAxis : {
            type : "category",
            name : "各大应用市场",
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
                type : "bar",
                data :b
            },
            {
                //数据关联名字
                name : "申请",
                type : "bar",
                data : c
            }
        ]

    }
    //图形实例化
    myCharts.setOption(option);
    function dian(type){
        var url='';
        if(type==1){
            url='/manage/channel';
        }else if(type==2){
            url='/manage/channel/monthUser';
        }else if(type==3){
            url='/manage/channel/dayApply';
        }else if(type==4){
            url='/manage/channel/dayapplyUser';
        }else if(type==5){
            url='/manage/channel/monthApply';
        }else if(type==6){
            url='/manage/channel/monthapplyUser';
        }else if(type==7){
            url='/manage/channel/dayFen';
        }
        window.location.href=url;
    }
</script>
</body>
</html>