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
<body>
    
    <div class="main_body"> 
        <a href="<?php echo U('index');?>" class="home"><img class="f" src="<?php echo ($static_path); ?>images/logo1.png" alt=""><img class="b" src="<?php echo ($static_path); ?>images/logo2.png" alt=""></a>
        <div class="common_heading">PARTNERS | 合作品牌</div>
        <!-- <div class="partners">      
            
            <ul class="partners_list clearfix">
                <li><a href=""><img src="<?php echo ($static_path); ?>images/p_logo01.jpg" alt=""></a></li>
                <li><a href=""><img src="<?php echo ($static_path); ?>images/p_logo02.jpg" alt=""></a></li>
                <li><a href=""><img src="<?php echo ($static_path); ?>images/p_logo03.jpg" alt=""></a></li>
                <li><a href=""><img src="<?php echo ($static_path); ?>images/p_logo04.jpg" alt=""></a></li>
                <li><a href=""><img src="<?php echo ($static_path); ?>images/p_logo05.jpg" alt=""></a></li>
                <li><a href=""><img src="<?php echo ($static_path); ?>images/p_logo06.jpg" alt=""></a></li>
                <li><a href=""><img src="<?php echo ($static_path); ?>images/p_logo07.jpg" alt=""></a></li>
                <li><a href=""><img src="<?php echo ($static_path); ?>images/p_logo08.jpg" alt=""></a></li>
                <li><a href=""><img src="<?php echo ($static_path); ?>images/p_logo09.jpg" alt=""></a></li>
                <li><a href=""><img src="<?php echo ($static_path); ?>images/p_logo10.jpg" alt=""></a></li>
                <li><a href=""><img src="<?php echo ($static_path); ?>images/p_logo11.jpg" alt=""></a></li>
                <li><a href=""><img src="<?php echo ($static_path); ?>images/more.jpg" alt=""></a></li>
            </ul>
        </div> -->
         <div class="partners_content">
                <div class="partners_slide">
                    <ul>
                    <?php if(is_array($partners_ad)): $i = 0; $__LIST__ = $partners_ad;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><img src="<?php echo ($vo["pic"]); ?>" alt=""></li><?php endforeach; endif; else: echo "" ;endif; ?>
                    </ul>
                </div>
                <div class="partners_hd">
                    <ul>
                        <?php if(is_array($partners_ad)): $i = 0; $__LIST__ = $partners_ad;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li></li><?php endforeach; endif; else: echo "" ;endif; ?>
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