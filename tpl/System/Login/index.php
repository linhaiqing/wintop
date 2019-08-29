<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN""http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>后台登录 - {$config.site_name}</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="{$static_path}login/login.css"/>
	</head>
	<body>
		<div id="login">
			<h1>{$config.site_name} - 后台登录</h1>
			<form method="post" id="form">
				<p>
					<label>用户名：</label>
					<input class="text-input" type="text" name="account" id="account" value="{$_GET.account}"/>
				</p>
				<p>
					<label>密码：</label>
					<input class="text-input" type="password" name="pwd" id="pwd" value="{$_GET.pwd}"/>
				</p>
				<p>
					<label>验证码：</label>
					<input class="text-input" type="text" id="verify" style="width:60px;" maxlength="4" name="verify"/>
					<span id="verify_box">
						<img src="{:U('Login/verify')}" id="verifyImg" onclick="fleshVerify('{:U('Login/verify')}')" title="刷新验证码" alt="刷新验证码"/>
						<a href="javascript:fleshVerify('{:U('Login/verify')}')" id="fleshVerify">刷新验证码</a>
					</span>
				</p>
				<p class="btn_p">
					<input class="button" type="submit" value="登录后台">
				</p>
			</form>	
		</div>
		<script type="text/javascript" src="{:C('JQUERY_FILE')}"></script>
		<script type="text/javascript">
			if(self!=top){window.top.location.href="{:U('Index/index')}";}
			var static_public="{$static_public}",static_path="{$static_path}",login_check="{:U('Login/check')}",system_index="{:U('Index/index')}";
		</script>
		<script type="text/javascript" src="{$static_path}login/login.js"></script>
	</body>
</html>