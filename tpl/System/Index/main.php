<include file="Public:header" />
	<div class="mainbox">
		<link rel="stylesheet" type="text/css" href="{$static_path}css/main.css" />
		<if condition="$merchant_verify_count || $group_verify_count">
			<h2>您网站待审核的 商家有 <a style="cursor:pointer;color:red;" target="_top" href="{:U('Index/index',array('module'=>'Merchant','action'=>'wait_merchant'))}">{$merchant_verify_count}</a> 个，店铺有 <a style="cursor:pointer;color:red;" target="_top" href="{:U('Index/index',array('module'=>'Merchant','action'=>'wait_store'))}">{$merchant_verify_store_count}</a> 个，{$config.group_alias_name}有 <a style="cursor:pointer;color:red;" target="_top" href="{:U('Index/index',array('module'=>'Group','action'=>'wait_product'))}">{$group_verify_count}</a> 个</h2>
		</if>
		<div id="Profile" class="list">
			<h1><b>个人信息</b><span>Profile&nbsp; Info</span></h1>
			<ul>
				<li><span>会员名:</span>{$system_session.account}</li>
				<li><span>会员组:</span>超级管理员</li>
				<li><span>最后登陆时间:</span>{$system_session.last_time|date='Y-m-d H:i:s',###}</li>
				<li><span>最后登陆IP/地址:</span>{$system_session.last_ip|long2ip=###} / {$system_session.last.country} {$system_session.last.area}</li>
				<li><span>登陆次数:</span>{$system_session.login_count}</li>
			</ul>
		</div>
		<if condition="$system_session['level'] eq 2">
		<div id="sitestats">
			<h1><b>网站统计</b><span>Site &nbsp; Stats</span></h1>
			<div>
				<ul>
					<li style="background:#457CB5;line-height:44px;color:white;font-weight:bold;">网站</li>
					<li><b>用户总数</b><br><span>{$website_user_count}</span></li>
					<li><b><a href="#">商户总数</a></b><br>
				  <span>{$website_merchant_count}</span></li>
					<li><b><a href="#">店铺总数</a></b><br>
				  <span>{$website_merchant_store_count}</span></li>
					<li><b><script src="http://s19.cnzz.com/stat.php?id=4297110&web_id=4297110" language="JavaScript"></script></b><span>{$website_merchant_count}</span></li>
					<li><b><a href="#">今日下单</a></b><span>{$group_today_order_count}</span></li>
					<li style="background:#3A6EA5;line-height:44px;color:white;font-weight:bold;">{$config.group_alias_name}</li>
					<li><b>{$config.group_alias_name}总数</b><br><span>{$group_group_count}</span></li>
					<li><b><a href="#">今日订单</a></b><span>{$group_today_order_count}</span></li>
					<li><b><a href="#">本周订单</a></b><span>{$group_week_order_count}</span></li>
					<li><b><a href="#">本月订单</a></b><span>{$group_month_order_count}</span></li>
					<li><b><a href="#">今年订单</a></b><span>{$group_year_order_count}</span></li>
					<li style="background:#FF658E;line-height:44px;color:white;font-weight:bold;">{$config.meal_alias_name}</li>
					<li><b>店铺总数</b><span>{$meal_store_count}</span></li>
					<li><b>今日订单</b><span>{$meal_today_order_count}</span></li>
					<li><b>本周订单</b><span>{$meal_week_order_count}</span></li>
					<li><b>本月订单</b><span>{$meal_month_order_count}</span></li>
					<li><b>今年订单</b><span>{$meal_year_order_count}</span></li>
				</ul>
			</div>
		</div> 
		</if>
		<div id="system"  class="list">
			<h1><b>系统信息</b><span>System&nbsp; Info</span></h1>
			<ul>
				<volist name="server_info" id="vo">
					<li><span>{$key}:</span>{$vo}</li>
				</volist>
			</ul>
		</div>
		<if condition="$system_session['level'] eq 2">
		<div id="system"  class="list">
			<h1><b>官方动态</b><span>Soft &nbsp; Update</span></h1>
			<ul id="official_news">
				<li><a href="http://bbs.sasadown.cn">莎莎源码论坛<br />
			    <br />
				  <br />
				</a></li>
			</ul>
		</div>
		</if>
	</div>
	<div id="verify_merchant_list" style="display:none;">
		<table>
			<volist name="merchant_verify_list" id="vo">
				<tr>
					<td><a href="{:U('Index/index',array('module'=>'Merchant','action'=>'index','url'=>urlencode(U('Merchant/index',array('keyword'=>$vo['mer_id'],'searchtype'=>'mer_id')))))}" target="_blank">{$vo.name}</a></td>
				</tr>
			</volist>
		</table>
	</div>
	<div id="verify_merchant_store_list" style="display:none;">
		<table>
			<volist name="merchant_verify_store_list" id="vo">
				<tr>
					<td><a href="{:U('Index/index',array('module'=>'Merchant','action'=>'index','url'=>urlencode(U('Merchant/store',array('mer_id'=>$vo['mer_id'])))))}" target="_blank">{$vo.name}</a></td>
				</tr>
			</volist>
		</table>
	</div>
	<div id="verify_group_list" style="display:none;">
		<table>
			<volist name="group_verify_list" id="vo">
				<tr>
					<td><a href="{:U('Index/index',array('module'=>'Group','action'=>'product','url'=>urlencode(U('Group/product',array('keyword'=>$vo['group_id'],'searchtype'=>'group_id')))))}" target="_blank">{$vo.s_name}</a></td>
				</tr>
			</volist>
		</table>
	</div>
	
<include file="Public:footer"/>