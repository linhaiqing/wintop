<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset={:C('DEFAULT_CHARSET')}" />
		<title>网站后台管理 Powered by linhaiqing.com</title>
		<script type="text/javascript">
			//if(self==top){window.top.location.href="{:U('Index/index')}";}
			var kind_editor=null,static_public="{$static_public}",static_path="{$static_path}",system_index="{:U('Index/index')}",choose_province="{:U('Area/ajax_province')}",choose_city="{:U('Area/ajax_city')}",choose_area="{:U('Area/ajax_area')}",choose_circle="{:U('Area/ajax_circle')}",choose_map="{:U('Map/frame_map')}",get_firstword="{:U('Words/get_firstword')}",frame_show=<if condition="$_GET['frame_show']">true<else/>false</if>;
 var  meal_alias_name = "{$config.meal_alias_name}";
		</script>
		<link rel="stylesheet" type="text/css" href="{$static_path}css/style.css" />
		<script type="text/javascript" src="{:C('JQUERY_FILE')}"></script> 
		<script type="text/javascript" src="{$static_public}js/jquery.form.js"></script>
		<script type="text/javascript" src="{$static_public}js/jquery.cookie.js"></script>
		<script type="text/javascript" src="{$static_public}js/jquery.validate.js"></script> 
		<script type="text/javascript" src="{$static_public}js/date/WdatePicker.js"></script> 
		<script type="text/javascript" src="{$static_public}js/jquery.colorpicker.js"></script>
		<script type="text/javascript" src="{$static_path}js/common.js"></script>
	</head>
	<body width="100%" <if condition="$bg_color">style="background:{$bg_color};"</if>>