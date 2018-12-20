<link rel="stylesheet" type="text/css" href="<?php echo(BASEURL)?>/css/index.css">
<link rel="stylesheet" href="<?php echo(BASEURL)?>/css/bootstrap.min.css">
<script type="text/javascript" src="<?php echo(BASEURL)?>/plugins/layui/layui.js"></script>
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
<form action="/manage/merchant/xinxi" method="get">
    <input type="hidden" name="id" id="id" value="<?php echo $this->input->get('id');?>"/>
手机号：<input type="text" name="mobile" id="mobile" value="<?php echo $this->input->get('mobile');?>" /> &nbsp;&nbsp;&nbsp;&nbsp;
    <span class="rowspan">
        &nbsp;&nbsp;&nbsp;&nbsp;时间：
    </span>
    <?php $starttime=$this->input->get('starttime');$endtime=$this->input->get('endtime');?>
    <label class="rowlabel">
        <input size="16" type="text" id="datetimeStart" placeholder="点击选择开始时间"  name="starttime" readonly class="form-horizontal" value="<?php echo $starttime!=''?$starttime:'';?>" >-
    </label>
    <label class="rowlabel">
        <input size="16" type="text" id="datetimeEnd" placeholder="点击选择结束时间"  name="endtime" readonly class="form-horizontal" value="<?php echo $endtime!=''?$endtime:'';?>">
    </label>
    <button type="submit" class="btn btn-info">搜索 </button>
     <button type="button" class="btn btn-success add" onclick="excel()">&nbsp;&nbsp;点击下载Excel&nbsp;&nbsp;</button>
 </form>

<table class="table table-bordered table-hover" style="width:100%;">
   <tr>
       <th>姓名</th>
       <th>年龄</th>
       <th>身份证号码</th>
       <th>手机号</th>
       <th>注册时间</th>
   </tr>
   <?php if(!empty($data)){
       foreach($data as $v){ ?>
           <tr>
               <td><?php echo $v->name;?></td>
               <td><?php echo $v->age;?></td>
               <td><?php echo $v->card;?></td>
               <td><?php echo $v->mobile;?></td>
               <td><?php echo $v->createdTime;?></td>
           </tr>
       <?php } }else{ ?>
       <tr data-index="0" class="">
           <td colspan ="8">
               <center>暂无数据</center>
           </td>
       </tr>
   <?php }?>
</table>
<?php echo $pages;?>
<link href="/manage/datetime/bootstrap-datetimepicker.min.css" rel="stylesheet" />
<script src="/manage/datetime/bootstrap-datetimepicker.min.js"></script>
<script src="/manage/datetime/bootstrap-datetimepicker.zh-CN.js"></script>
<script>
    $(function(){
        $("#datetimeStart").datetimepicker({
            format: 'yyyy-mm-dd', minView:'month', language: 'zh-CN', autoclose:true, startDate:'2018-9-01'
        }).on('hide', function(event) {
            event.preventDefault();
            event.stopPropagation();
            var endTime = event.date;
            $('#datetimeEnd').datetimepicker('setStartDate',endTime);
        });
        $("#datetimeEnd").datetimepicker({
            format: 'yyyy-mm-dd', minView:'month', language: 'zh-CN', autoclose:true, startDate:'2018-9-01'
        }).on('hide', function(event) {
            event.preventDefault();
            event.stopPropagation();
            var startTime = event.date;
            $('#datetimeStart').datetimepicker('setEndDate',startTime);
        });

    });
    
    function excel(){
        //console.log(123);
        var id = $('#id').val();
        var mobile = $('#mobile').val();
        var starttime = $('#datetimeStart').val();
        var endtime = $('#datetimeEnd').val();
        var link ="/manage/merchant/exexl" + '?id='+id+ '&mobile='+ mobile + '&starttime='+starttime+ '&endtime='+endtime;
        window.location.href=link; 
    }
    
</script>