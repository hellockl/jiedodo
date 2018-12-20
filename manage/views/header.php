<div class="homeTop">
    <b id="RenWu"  <?php if(strlen($title)>=15){echo 'style="width: 90px;"';}?>><?php echo $title;?></b>
    <a href="/manage/kehu/logout"><em><img src="/manage/img/nav/tuichu.png"></em></a>
    <span><?php echo $this->session->admin->username;?></span>
</div>