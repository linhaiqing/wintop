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
	<div class="container">
		<div class="slide">
			<a href="index.php" class="home"><img class="f" src="{$static_path}images/logo1.png" alt=""><img class="b" src="{$static_path}images/logo2.png" alt=""></a>
			<div class="hd">
				<ul>
					<volist name="lunbo_adver" id="vo">
                    <li></li>
                    </volist>
				</ul>
			</div>
			<div class="slide_container">
				<ul>
					<volist name="lunbo_adver" id="vo">
                    <li><a href=""><img src="{$vo.pic}" alt=""></a></li>
                    </volist>
				</ul>
			</div>
		</div>
		<foreach name="brandprofile" item="vo">
		<div class="brand_block fadeIn">
			<img src="{$vo.pic}" alt="">
		</div>
		</foreach>
		
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