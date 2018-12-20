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
            <div class="BoxList" >
                <form action="" method="post">
                    <div class="row" style="width: 98%; margin:0 auto; margin-top:20px;">
                        <span class="rowspan">
                           &ensp;&ensp; &ensp;&ensp; &ensp;&ensp; &ensp;&ensp; &ensp;密码长度为6-15位
                        </span>

                    </div>
                    <div class="row" style="width: 98%; margin:0 auto; margin-top:20px;">
                        <span class="rowspan">
                          &ensp;&ensp;旧密码：
                        </span>
                        <label class="rowlabel">
                            <input type="password" required name="pa">
                        </label>

                    </div>
                    <div class="row" style="width: 98%; margin:0 auto; margin-top:20px;">
                        <span class="rowspan">
                           &ensp;&ensp;新密码：
                        </span>
                        <label class="rowlabel">
                            <input type="password" required name="pass" >
                        </label>

                    </div>
                    <div class="row" style="width: 98%; margin:0 auto; margin-top:20px;">
                        <span class="rowspan">
                          确认密码：
                        </span>
                        <label class="rowlabel">
                            <input type="password" required name="pass1" >
                        </label>

                    </div>
                    <div class="row" style="width: 98%; margin:0 auto; margin-top:20px;">
                        &ensp;&ensp; &ensp;&ensp; &ensp;&ensp; &ensp;&ensp;<button type="submit" class="btn btn-info">&nbsp;&nbsp;提 交&nbsp;&nbsp;</button>
                    </div>

                </form>
            </div>
        </div>
</body>
<script>
    if(a!=''){
        layui.use([], function(){layer.msg(a); });
    }
</script>
</html>