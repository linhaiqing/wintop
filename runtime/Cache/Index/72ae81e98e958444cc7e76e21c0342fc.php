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
<body>
	<div class="container">
		<div class="slide">
			<a href="index.php" class="home"><img class="f" src="<?php echo ($static_path); ?>images/logo1.png" alt=""><img class="b" src="<?php echo ($static_path); ?>images/logo2.png" alt=""></a>
			<div class="hd">
				<ul>
					<?php if(is_array($lunbo_adver)): $i = 0; $__LIST__ = $lunbo_adver;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li></li><?php endforeach; endif; else: echo "" ;endif; ?>
				</ul>
			</div>
			<div class="slide_container">
				<ul>
					<?php if(is_array($lunbo_adver)): $i = 0; $__LIST__ = $lunbo_adver;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a href=""><img src="<?php echo ($vo["pic"]); ?>" alt=""></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
				</ul>
			</div>
		</div>
		<?php if(is_array($brandprofile)): foreach($brandprofile as $key=>$vo): ?><div class="brand_block fadeIn">
			<img src="<?php echo ($vo["pic"]); ?>" alt="">
		</div><?php endforeach; endif; ?>
		
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

		$(window).scroll(function(event) {
			var s = $(window).scrollTop();
			// console.log(s);
			if (s > 300) {
				$('.j2').addClass('fadeIn');
			}
			if (s > 700) {
				$('.j3').addClass('fadeIn');
			}
			if (s > 1500) {
				$('.j4').addClass('fadeIn');
			}
		});

	</script>
</body>
</html>