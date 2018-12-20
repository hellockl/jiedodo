<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <meta name="format-detection" content="email=no">
    <link rel="stylesheet" type="text/css" href="/manage/css/index.css">
    <title><?php echo $data->title?$data->title:'';?></title>
</head>
<body style="background: #fff;">
<header class="detectionTop"  style="margin:0.5rem 0 0 0.5rem; ">
    <h2><?php echo $data->title?$data->title:'';?></h2>
</header>
<section class="enrollBoxs" style="margin-top:0.5rem; padding-bottom: 5%;">
    <?php echo $data->content?$data->content:'';?>
</section>
</body>
</html>
