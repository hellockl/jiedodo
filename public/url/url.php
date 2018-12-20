<!DOCTYPE html>
<html lang=zh-Hans>
<head>
    <meta charset=utf-8>
    <title></title>
    <meta http-equiv=X-UA-Compatible content="IE=edge">
    <meta name=format-detection content="telephone=no">
    <meta name=format-detection content="email=no">
    <meta name=apple-mobile-web-app-capable content=yes>
    <meta name=apple-mobile-web-app-status-bar-style content=black>
    <meta name=full-screen content=yes>
    <meta name=browsermode content=application>
    <meta name=x5-orientation content=portrait>
    <meta name=x5-fullscreen content=true>
    <meta name=x5-page-mode content=app>
    <meta name=viewport content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
    <style>
        * {
            padding: 0;
            margin: 0;
            -webkit-box-sizing: border-box;
                    box-sizing: border-box;
        }
        body {
            max-width: 640px;
            height: 100vh;
            position: relative;
            background: #fff;
            margin: auto;
        }
        body .main .top {
            width: 100vw;
            height: 50vh;
            position: relative;
        }
        .main {
            position: relative;
        }
        .left, .right {
            height: 40vh;
            width: 30vw;
            position: absolute;
            top: 0;
        }
        .left {
            left: 0;
            background: url(/url/left.png) no-repeat left/contain;
        }
        .right {
            right: 0;
            background: url(/url/right.png) no-repeat right/contain;
        }
        .logo {
            width: 140px;
            height: 140px;
            position: absolute;
            border-radius: 50%;
            left: 0;
            right: 0;
            margin: auto;
            bottom: 70px;
        }
        .logo img {
            max-width: 100%;
            border-radius: 50%;
        }
        .text {
            position: absolute;
            width: 70%;
            margin-left: 15%;
            bottom: 0;
            height: 4em;
            border-bottom: 1px solid #ddd;
        }
        h1 {
            text-align: center;
            font-weight: 400;
        }
        .bottom {
            position: relative;
        }
        .content {
            margin-top: 2em;
            text-align: center;
            color: gray;
        }
        button {
            background: #32b2a7;
            outline: none;
            border: none;
            padding: 12px 60px;
            border-radius: 30px;
            color: #fff;
            font-size: 1em;
            margin-top: 1.5em;
        }
    </style>
</head>
<body>
    <div class="main">
        <div class="top">
            <div class="left"></div>
            <div class="right"></div>
            <div class="logo">
                <img src="/url/logo_hd.jpg" alt="">
            </div>
            <div class="text">
                <h1>板凳借钱</h1>
            </div>
        </div>
        <div class="bottom">
            <div class="content">
                <a href="/url/bdjq.apk"><button>下载安装</button></a>
            </div>
        </div>
    </div>
</body>
</html>