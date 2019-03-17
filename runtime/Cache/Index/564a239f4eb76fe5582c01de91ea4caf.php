<?php if (!defined('THINK_PATH')) exit();?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
    <title>WinTop官方网站</title>
    <link rel="stylesheet" href="<?php echo ($static_path); ?>css/main.css">
    <script type="text/javascript" src="<?php echo ($static_path); ?>js/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="<?php echo ($static_path); ?>js/jquery.SuperSlide.js"></script>
</head>
<style>
    .nav{width: 156px;}
    .nav li{margin-bottom:28px;}
    .nav_item h3{width: 94px;font-size: 9px;}
    .aside_menu{width: 180px;}
    .nav_item:hover span{
    left: 94px;
}
@media screen and (max-width: 980px){
     .nav li{margin-bottom:18px;}
   .nav{width: 130px;}
    .aside_menu{
        width: 140px;
        height: 440px;
    }
}
</style>
<body>
    <div class="index_container">
        <div class="top_brand">
            <audio id="sound" autoplay="autoplay" loop="loop" src="<?php echo ($static_path); ?>music/music.mp3"></audio>
            <div id="play_icon" class="music_icon scroll"></div>
            <div class="aside_menu">
                <div class="aside_container">
                    <span class="aside_left"></span>
                    <span class="aside_bottom"></span>
                    <div class="wintop_logo"><img src="<?php echo ($static_path); ?>images/wintop_logo.jpg" alt=""></div>
                    <ul class="nav">
                        <li>
                            <a href="<?php echo U('brand_profile');?>" class="nav_item">
                                <h3>BRAND</h3>
                                <span>品牌介绍</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo U('handicrafts');?>" class="nav_item">
                                <h3 >HANDICRAFTS</h3>
                                <span>匠心手作</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo U('products');?>" class="nav_item">
                                <h3>PRODUCT</h3>
                                <span>产品介绍</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo U('exhibition_profile');?>" class="nav_item">
                                <h3>SHOW ROOM</h3>
                                <span>产品展厅</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo U('partners');?>" class="nav_item">
                                <h3>PARTNERS</h3>
                                <span>合作伙伴</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo U('contactus');?>" class="nav_item">
                                <h3>CONTACT</h3>
                                <span>联系我们</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <img src="<?php echo ($index_top_adver["0"]["pic"]); ?>" alt="">
        </div>
       <div class="copyright">© 2017 Wintop Handbag. All rights reserved</div>
    </div>
    <script type="text/javascript">
        /**音乐播放器**/
        var play = true;
        $(function() {
            var sound = document.getElementById('sound');
            var player = $('#play_icon');
            player.click(function(event) {
                if (play) {
                    play = false;
                    sound.pause();
                    player.addClass('paused').removeClass('scroll');            
                }else {
                    play = true;
                    sound.play();
                    player.removeClass('paused').addClass('scroll');
                }
            });
        })

    </script>
</body>
</html>