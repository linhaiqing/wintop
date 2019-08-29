
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
<body>
    
    <div class="main_body"> 
        <a href="{:U('index')}" class="home"><img class="f" src="{$static_path}images/logo1.png" alt=""><img class="b" src="{$static_path}images/logo2.png" alt=""></a>
        <div class="common_heading">PARTNERS | 合作品牌</div>
        <!-- <div class="partners">      
            
            <ul class="partners_list clearfix">
                <li><a href=""><img src="{$static_path}images/p_logo01.jpg" alt=""></a></li>
                <li><a href=""><img src="{$static_path}images/p_logo02.jpg" alt=""></a></li>
                <li><a href=""><img src="{$static_path}images/p_logo03.jpg" alt=""></a></li>
                <li><a href=""><img src="{$static_path}images/p_logo04.jpg" alt=""></a></li>
                <li><a href=""><img src="{$static_path}images/p_logo05.jpg" alt=""></a></li>
                <li><a href=""><img src="{$static_path}images/p_logo06.jpg" alt=""></a></li>
                <li><a href=""><img src="{$static_path}images/p_logo07.jpg" alt=""></a></li>
                <li><a href=""><img src="{$static_path}images/p_logo08.jpg" alt=""></a></li>
                <li><a href=""><img src="{$static_path}images/p_logo09.jpg" alt=""></a></li>
                <li><a href=""><img src="{$static_path}images/p_logo10.jpg" alt=""></a></li>
                <li><a href=""><img src="{$static_path}images/p_logo11.jpg" alt=""></a></li>
                <li><a href=""><img src="{$static_path}images/more.jpg" alt=""></a></li>
            </ul>
        </div> -->
         <div class="partners_content">
                <div class="partners_slide">
                    <ul>
                    <volist name="partners_ad" id="vo">
                        <li><img src="{$vo.pic}" alt=""></li>
                    </volist>
                    </ul>
                </div>
                <div class="partners_hd">
                    <ul>
                        <volist name="partners_ad" id="vo">
                        <li></li>
                        </volist>
                    </ul>
                </div>
            </div>
    </div>
    <script type="text/javascript">
        $('.partners_content').slide({
                mainCell: '.partners_slide ul',
                titCell: '.partners_hd ul li',
                effect: 'leftLoop',
                autoPlay: true,
                interTime: 5000
            })
    </script>
</body>
</html>