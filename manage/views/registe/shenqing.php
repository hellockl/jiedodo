<link rel="stylesheet" type="text/css" href="<?php echo(BASEURL)?>/css/index.css">
<link rel="stylesheet" href="<?php echo(BASEURL)?>/css/bootstrap.min.css">
<script type="text/javascript" src="<?php echo(BASEURL)?>/js/layui/layui.js"></script>
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
<div class="UserBox">
   <table class="table table-bordered table-hover">
       <tr>
           <th>用户</th>
           <th>手机号</th>
           <th>时间</th>
       </tr>
       <?php if(!empty($data)){
           foreach($data as $v){ ?>
               <tr>
                   <td><img src="<?php echo $v->headImgUrl;?>" width="50px" /><?php echo $v->nickname;?></td>
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
</div>