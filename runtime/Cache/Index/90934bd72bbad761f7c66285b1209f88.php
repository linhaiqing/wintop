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
		<div class="top_brand fadeIn">
			<img src="<?php echo ($jiangxin["0"]["pic"]); ?>" alt="">
			<a href="index.php" class="home"><img class="f" src="<?php echo ($static_path); ?>images/logo1.png" alt=""><img class="b" src="<?php echo ($static_path); ?>images/logo2.png" alt=""></a>
		</div>
		<div class="exhibition_block fadeIn">
			<img src="<?php echo ($jiangxin["1"]["pic"]); ?>" alt="">
		</div>
		<div class="exhibition_block fadeIn">
			<img src="<?php echo ($jiangxin["2"]["pic"]); ?>" alt="">
		</div>
		<div class="exhibition_block fadeIn">
			<img src="<?php echo ($jiangxin["3"]["pic"]); ?>" alt="">
		</div>
	</div>
	
	
</body>
</html>