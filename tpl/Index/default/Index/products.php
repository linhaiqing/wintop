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
    .brand_list ul li a{color: #000;}
</style>
<body>
    <div class="container">
        <div class="slide">
            <a href="{:U('index')}" class="home"><img class="f" src="{$static_path}images/logo1.png" alt=""><img class="b" src="{$static_path}images/logo2.png" alt=""></a>
            <div class="hd">
                <ul>
                    <volist name="index_lunbo_adver" id="vo">
                    <li></li>
                    </volist>
                </ul>
            </div>
            <div class="slide_container">
                <ul>
                    <volist name="index_lunbo_adver" id="vo">
                    <li><a href=""><img src="{$vo.pic}" alt=""></a></li>
                    </volist>
                </ul>
            </div>
        </div>
        <div class="brand_list">
            <div class="products_row">
                <ul>
                    <volist name="products" id="vo">
                    <li><a href="{:U('detail',array('id'=>$vo['id']))}">
                        <div class="product_img"><img src="{$config.site_url}{$vo.front_pic}" class="front" alt=""><img src="{$config.site_url}{$vo.reserve_pic}" class="back" alt=""></div>
                        <span>{$vo.title}</span>
                        <p>{$vo.desc}</p></a>
                    </li>
                    </volist>
                </ul>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        
        $(function() {
            $('.slide').slide({
                mainCell: '.slide_container ul',
                titCell: '.hd ul li',
                effect: 'leftLoop',
                autoPlay: true,
                interTime: 5000
            })
        });

    </script>
</body>
</html>