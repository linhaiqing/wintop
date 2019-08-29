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
		<div class="top_brand">
			<img src="{$static_path}images/banner2.jpg" alt="">
			<a href="{:U('index')}" class="home"><img class="f" src="{$static_path}images/logo1.png" alt=""><img class="b" src="{$static_path}images/logo2.png" alt=""></a>
		</div>
		<div class="content">
			<div class="photo_exhibition clearfix">
				<div class="photo_left">
					<div class="photo_box1"><img src="{$static_path}images/photo01.jpg" class="fade_img img_responsive " alt=""></div>
					<div class="photo_text1">
						<h3><img src="{$static_path}images/photo_title01.png" class="img_responsive" alt=""></h3>
						<p>Inheritance of Chinese<br>Handicrafts Continous of Infinite</p>
						<span class="line_bar"></span>
					</div>
				</div>
				<div class="photo_right">
					<div class="photo_box2"><img src="{$static_path}images/photo02.jpg" class="fade_img img_responsive " alt=""></div>
					<div class="photo_text2">
						<h3><img src="{$static_path}images/photo_title02.png" class="img_responsive" alt=""></h3>
						<p>Inheritance of Chinese<br>Handicrafts Continous of Infinite<br>Continous of Infinite</p>
						<span class="line_bar"></span>
					</div>
				</div>
			</div>
			<div class="photos_list">
				<div class="photos_one">
					<div class="photos_box1"><img src="{$static_path}images/photo04.png" class="img_responsive" alt=""></div>
					<div class="photos_text3">
						<h3><img src="{$static_path}images/photo_title03.png" class="img_responsive" alt=""></h3>
						<p>Smile is an international language</p>
						<span class="line_bar"></span>
					</div>
				</div>
				<div class="photos_two">
					<div class="photos_box2"><img src="{$static_path}images/photo03.png" class="img_responsive" alt=""></div>
					<div class="photos_text4">
						<h3><img src="{$static_path}images/photo_title04.png" class="img_responsive" alt=""></h3>
						<p>A person who has a real talent<br>can feel the most happiness in the work</p>
						<span class="line_bar"></span>
					</div>
				</div>
				<div class="photos_three">
					<div class="photos_box3"><img src="{$static_path}images/photo05.png" class="img_responsive" alt=""></div>
					<div class="photos_text5">
						<h3><img src="{$static_path}images/photo_title05.png" class="img_responsive" alt=""></h3>
						<p>Smile like a warm spring breeze<br>bathed in our hearts</p>
						<span class="line_bar"></span>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		$(function() {

			$('.photo_left').on('mouseenter', function(){
				$('.photo_text1').css({
					'opacity': 1,
					'top': '320px'
				});
			});
			$('.photo_right').on('mouseenter', function(){
				$('.photo_text2').css({
					'opacity': 1,
					'top': '320px'
				});
			});
			$('.photos_one').on('mouseenter', function(){
				$('.photos_text3').css({
					'opacity': 1,
					'top': '-142px'
				});
			});
			$('.photos_two').on('mouseenter', function(){
				$('.photos_text4').css({
					'opacity': 1,
					'left': '265px'
				});
			});
			$('.photos_three').on('mouseenter', function(){
				$('.photos_text5').css({
					'opacity': 1,
					'left': '-184px'
				});
			});

			$(window).scroll(function(event) {
				var s = $(window).scrollTop();
				// console.log(s);
				if (s > 700) {
					$('.photos_box1').find('img').addClass('fadeRight_img');
					$('.photos_box2').find('img').addClass('fadeRight_img');
					$('.photos_box3').find('img').addClass('fadeRight_img');
				}
			});

		});
	</script>
</body>
</html>