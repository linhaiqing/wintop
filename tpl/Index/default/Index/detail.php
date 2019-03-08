<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
    <title>WinTop官方网站</title>
    <link rel="stylesheet" href="{$static_path}css/main.css">
    <script type="text/javascript" src="{$static_path}js/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="{$static_path}js/jquery.SuperSlide.js"></script>
</head>
<style>
.main_body{padding-top: 0;}
    .t1{width: 80%;float: left;}
    .t2{width: 20%;float: right;}
    .t2 p{display: inline-block;}
    .t22{margin-top: 620px;margin-left: 40px;}
    .home1 {
    display: block;
    width: 92px;
    height: 92px;
    position: absolute;
    bottom: 5px;
    right: 72px;
}
.top_brand{height: 680px;}
.brand_list{margin-top: 0;}
.brand_list ul li{width: 398px;}
.brand_list ul li img{width: 470px;height: 470px;}

.t3{width: 80%;float: left;}
.t4{width: 16%;float: right;background-color: #efefef;height: 416px;padding: 24px;}
.t4 h1{font-size:20px;}
.t4 h2{font-size:14px;}
@media screen and (max-width: 980px){
    .top_brand{height: 550px;}
    
    .brand_list ul li{margin:0;width: 370px;}
.brand_list ul li img {
    width: 370px;
    height: 370px;
}
.t4{height: 345px;padding: 10px;}
.t22{margin-top: 500px;}

}
</style>
<body style="width: 100%">
    <div class="main_body">
        <div class="top_brand">
            <div class="t1"><img src="{$config.site_url}{$product.big1}" alt=""  ></div>
            <div class="t2">
            <a href="javacript:;" class="home" onclick="window.history.back();"><img src="{$static_path}images/back.png" alt=""></a>
            <div class="t22">
                <p>{$product.title}</p>
                <p>{$product.desc}</p>
            </div>
            </div>
        </div>
        <div class="brand_list">
            <div class="t3">
                <ul>
                    <li>
                        <img src="{$config.site_url}{$product.big2}" class="front" alt="">
                    </li>
                    <li style="margin-left: 40px">
                        <img src="{$config.site_url}{$product.big3}" class="front" alt="">
                    </li>
                </ul>
            </div>
            <div class="t4" >
                <h1>{$product.r_title}</h1>
                <h2>{$product.r_info}</h2>
            </div>
        </div>
    </div>
</body>
</html>