<link rel="stylesheet" type="text/css" href="<?php echo(BASEURL)?>/css/index.css">
<link rel="stylesheet" href="<?php echo(BASEURL)?>/css/bootstrap.min.css">
<script type="text/javascript" src="<?php echo(BASEURL)?>/js/layui/layui.js"></script>
<script src="<?php echo(BASEURL)?>/js/jquery-2.1.1.min.js"></script>
<script src="<?php echo(BASEURL)?>/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo(BASEURL)?>/js/index.js"></script>
<script type="text/javascript" src="/manage/js/layer/layer.js"></script>
<style type="text/css">
    td,th{
        font-size: 14px;
    }
    .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th{
        border-top: none;
    }
    .table>thead>tr>th {
        border-bottom: none;
    }
     tr td,th{text-align: center !important;vertical-align: middle !important;}
</style>
<div class="UserBox" style='width:100%'>

    <button type="button" class="btn btn-success add" id='<?php echo $this->input->get('id');?>' >&nbsp;&nbsp;新 增&nbsp;&nbsp;</button>

    <table  class="table table-bordered table-hover">
       <tr>
           <th>序号</th>
           <th>充值金额</th>
           <th>充值时间</th>
           <th>操作</th>
       </tr>
       <?php if(!empty($data)){
            $i=1;
           foreach($data as $v){ ?>
               <tr>
                   <td><?php echo $i;$i++;?></td>
                   <td><?php echo $v->money;?></td>
                   <td><?php echo $v->createdTime;?></td>
                   <td><button type="button" id="<?php echo $v->id;?>"  class="btn btn-warning shanchu">删除</button></td>
               </tr>
       <?php } ?>
            <tr data-index="0" class="">
               <td colspan ="8">
                   <center>累积充值金额：<?php echo $count;?></center>
               </td>
           </tr>
       <?php }else{ ?>

           <tr data-index="0" class="">
               <td colspan ="8">
                   <center>暂无数据</center>
               </td>
           </tr>
       <?php }?>
    </table>
</div>
<script>
    $('.add').on('click', function(){
        layer.closeAll();
        var id=$(this).attr('id');
        layer.open({
            type: 2 //此处以iframe举例
            ,title: '新增'
            ,area: ['50%', '80%']
            ,shade: 0.6
            ,maxmin: false
            ,content:"<?php echo BASEURL;?>/registe/advanceAdd?id="+id
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
                url: "<?php echo BASEURL;?>/registe/advanceDel",
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