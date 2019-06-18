<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
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
    .brand_list ul li a{color: #000;}
</style>
<body>
    <div class="container">
        <div class="slide">
            <a href="<?php echo U('index');?>" class="home"><img class="f" src="<?php echo ($static_path); ?>images/logo1.png" alt=""><img class="b" src="<?php echo ($static_path); ?>images/logo2.png" alt=""></a>
            <div class="hd">
                <ul>
                    <?php if(is_array($index_lunbo_adver)): $i = 0; $__LIST__ = $index_lunbo_adver;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li></li><?php endforeach; endif; else: echo "" ;endif; ?>
                </ul>
            </div>
            <div class="slide_container">
                <ul>
                    <?php if(is_array($index_lunbo_adver)): $i = 0; $__LIST__ = $index_lunbo_adver;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a href=""><img src="<?php echo ($vo["pic"]); ?>" alt=""></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
                </ul>
            </div>
        </div>
        <div class="brand_list">
            <div class="products_row">
                <ul>
                    <?php if(is_array($products)): $i = 0; $__LIST__ = $products;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a href="<?php echo U('detail',array('id'=>$vo['id']));?>">
                        <div class="product_img"><img src="<?php echo ($config["site_url"]); echo ($vo["front_pic"]); ?>" class="front" alt=""><img src="<?php echo ($config["site_url"]); echo ($vo["reserve_pic"]); ?>" class="back" alt=""></div>
                        <span><?php echo ($vo["title"]); ?></span>
                        <p><?php echo ($vo["desc"]); ?></p></a>
                    </li><?php endforeach; endif; else: echo "" ;endif; ?>
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