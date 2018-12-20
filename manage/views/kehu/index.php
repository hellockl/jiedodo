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
                            <input type="text" name="mobile" value="<?php echo $this->input->get('mobile');?>">
                        </label>

                        <span class="rowspan">
                          日期：
                        </span>
                        <label class="rowlabel">
                            <input type="text" id="create_time" name="ks" value="<?php echo $this->input->get('ks');?>" placeholder="请点击选择时间"   style="width: 150px;">
                        </label><span class="rowspan">
                         &nbsp;&nbsp;-&nbsp;&nbsp;
                        </span>
                        <label class="rowlabel">
                            <input type="text" id="endDate" name='js' value="<?php echo $this->input->get('js');?>" placeholder="请点击选择时间" readonly class="form_datetime" style="width: 150px;">
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
                              <th>序号</th>
                              <th>姓名</th>
                              <th>年龄</th>
                              <th>身份证号码</th>
                              <th>手机号</th>
                              <th>注册时间</th>
                          </tr>
                          </thead>
                          <tbody>
                            <?php if(!empty($data)){
                                $i=1;
                                $i*=$p;
                                foreach($data as $v){ ?>
                                    <tr>
                                        <td><?php echo $i;$i++;?></td>
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
                          </tbody>
                        </table>
                    </div>
                    <?php echo $pages?>
                </div>
            </div>
        </div>
</body>

<script src="/js/layui/layui.js" charset="utf-8"></script>
<script type="text/javascript">
    layui.use('laydate', function(){
        var laydate = layui.laydate;
        //时间选择器
        laydate.render({
            elem: '#create_time'
            ,type: 'datetime'
        });
        laydate.render({
            elem: '#endDate'
            ,type: 'datetime'
        });
    });
</script>
</html>