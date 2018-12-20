<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>后台管理系统</title>
    <link rel="stylesheet" href="<?php echo(BASEURL)?>/css/bootstrap.min.css">
    <script src="<?php echo(BASEURL)?>/js/jquery-2.1.1.min.js"></script>
    <script src="<?php echo(BASEURL)?>/js/bootstrap.min.js"></script>
</head>
<body>
<div class="rghtDiv" >
    <div class="BoxList">
        <div class="" >
            <div class="AccountNumberTaeb">
                <table class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>名称</th>
                        <th>商标</th>
                        <th>简介</th>
                        <th>借款额度</th>
                        <th>申请时间</th>
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
                                <td><?php echo $v->createdTime;?></td>
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
        </div>
    </div>
</div>
</body>
</html>