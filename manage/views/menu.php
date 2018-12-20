<?php date_default_timezone_set('PRC'); ?>
<!--左侧-left-->
<?php if(isset($this->session->admin->code)){ ?>
        <div class="LeftDiv">
        <div class="logo"><img src="/manage/img/nav/logo.png"></div>
        <div class="AdminText">
            <em><img src="/manage/img/nav/yonghu.png"></em>
            <span><?php echo $this->session->admin->name;?></span>
        </div>
        <ul>
            <li <?php $contro=$this->router->method; echo $contro=='index'?'class="hover"':'';?> >
                <a href="<?php echo(BASEURL)?>/kehu">
                    <i></i>
                    <span>注册用户</span>
                    <em><img src="/manage/img/nav/jiantou.png"></em>
                </a>
            </li>
            <li <?php $contro=$this->router->method; echo $contro=='mima'?'class="hover"':'';?> >
            <a href="<?php echo(BASEURL)?>/kehu/mima">
                <i></i>
                <span>修改密码</span>
                <em><img src="/manage/img/nav/jiantou.png"></em>
            </a>
            </li>
        </ul>
    </div>
<?php }else{ ?>
<div class="LeftDiv">
        <div class="logo"><img src="/manage/img/nav/logo.png"></div>
        <div class="AdminText">
            <em><img src="/manage/img/nav/yonghu.png"></em>
            <span><?php echo $this->session->admin->name;?></span>
        </div>
        <ul>
            <li <?php $contro=$this->router->class; echo $contro=='home'?'class="hover"':'';?> >
                <a href="<?php echo(BASEURL)?>/home">
                    <i><img src="/manage/img/nav/shouye.png"></i>
                    <span>首 页</span>
                    <em><img src="/manage/img/nav/jiantou.png"></em>
                </a>
            </li>
            <li <?php echo $contro=='registe'?'class="hover"':'';?>>
                <a href="<?php echo(BASEURL)?>/registe">
                    <i></i>
                    <span>贷款商家</span>
                    <em><img src="/manage/img/nav/jiantou.png"></em>
                </a>
            </li>
            <li <?php echo $contro=='type'?'class="hover"':'';?>>
                <a href="<?php echo(BASEURL)?>/type">
                    <i></i>
                    <span>贷款分类</span>
                    <em><img src="/manage/img/nav/jiantou.png"></em>
                </a>
            </li>
            <li <?php echo $contro=='news'?'class="hover"':'';?>>
                <a href="<?php echo(BASEURL)?>/news">
                    <i></i>
                    <span>攻 略</span>
                    <em><img src="/manage/img/nav/jiantou.png"></em>
                </a>
            </li>
            <li <?php echo $contro=='card'?'class="hover"':'';?>>
                <a href="<?php echo(BASEURL)?>/card">
                    <i></i>
                    <span>信用卡</span>
                    <em><img src="/manage/img/nav/jiantou.png"></em>
                </a>
            </li>

            <li <?php echo $contro=='banner'?'class="hover"':'';?>>
                <a href="<?php echo(BASEURL)?>/banner">
                    <i></i>
                    <span>轮播图</span>
                    <em><img src="/manage/img/nav/jiantou.png"></em>
                </a>
            </li>
            <li <?php echo $contro=='adv'?'class="hover"':'';?>>
                <a href="<?php echo(BASEURL)?>/adv">
                    <i></i>
                    <span>广 告</span>
                    <em><img src="/manage/img/nav/jiantou.png"></em>
                </a>
            </li>
            <li <?php echo $contro=='user'?'class="hover"':'';?>>
                <a href="<?php echo(BASEURL)?>/user">
                    <i></i>
                    <span>用 户</span>
                    <em><img src="/manage/img/nav/jiantou.png"></em>
                </a>
            </li>
            <li <?php echo $contro=='merchant' && $method!='tongji'?'class="hover"':'';?>>
                <a href="<?php echo(BASEURL)?>/merchant">
                    <i></i>
                    <span>渠 道</span>
                    <em><img src="/manage/img/nav/jiantou.png"></em>
                </a>
            </li>
            <li <?php echo $contro=='merchant' && $method=='tongji'?'class="hover"':'';?>>
                <a href="<?php echo(BASEURL)?>/merchant/tongji">
                    <i></i>
                    <span>统 计</span>
                    <em><img src="/manage/img/nav/jiantou.png"></em>
                </a>
            </li>            
            <li <?php echo $contro=='config'?'class="hover"':'';?>>
                <a href="<?php echo(BASEURL)?>/config">
                    <i></i>
                    <span>配 置</span>
                    <em><img src="/manage/img/nav/jiantou.png"></em>
                </a>
            </li>
            <li <?php echo $contro=='edition' && $this->router->method=='index'?'class="hover"':'';?>>
                <a href="<?php echo(BASEURL)?>/edition">
                    <i></i>
                    <span>Android版本</span>
                    <em><img src="/manage/img/nav/jiantou.png"></em>
                </a>
            </li>
            <li <?php echo $contro=='edition' && $this->router->method=='ios'?'class="hover"':'';?>>
                <a href="<?php echo(BASEURL)?>/edition/ios">
                    <i></i>
                    <span>IOS版本</span>
                    <em><img src="/manage/img/nav/jiantou.png"></em>
                </a>
            </li>
        </ul>
</div>
<?php }?>
<script src="/manage/js/jquery-2.1.1.min.js"></script>
<script src="/manage/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/manage/js/layui/layui.js"></script>
<script type="text/javascript" src="/manage/js/layer/layer.js"></script>
<script type="text/javascript">
    $("body").css("background","url('/manage/img/nav/bj.jpg') left repeat-y")
</script>
<style>
    tr td,th{text-align: center !important;vertical-align: middle !important;}
    .middle{text-align:left !important;vertical-align: middle !important;}
</style>
