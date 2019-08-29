<include file="Public:header"/>
<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
	<tr>
		<th width="180">菜品名称</th>
		<th>单价</th>
		<th>数量</th>
	</tr>
	<volist name="order['info']" id="vo">
	<tr>
		<th width="180">{$vo['name']}</th>
		<th>{$vo['price']}</th>
		<th>{$vo['num']}</th>
	</tr>
	</volist>
	<tr>
		<th colspan="3">支付状态:　
		<if condition="empty($order['paid'])">未支付
		<elseif condition="$order['pay_type'] eq 'offline' AND empty($order['third_id'])" />线下未支付
		<elseif condition="$order['paid'] eq 2"  /><span style="color:green">已付￥{$order['pay_money']}</span>，<span style="color:red">未付￥{$order['price'] - $order['pay_money']}</span>
		<else /><span style="color:green">全额支付</span>
		</if>
		</th>
	</tr>
	<tr>
		<th colspan="3">余额支付金额:￥ {$order['balance_pay']}</th>
	</tr>
	<tr>
		<th colspan="3">在线支付金额:￥ {$order['payment_money']}</th>
	</tr>
	<tr>
		<th colspan="3">使用商户余额:￥ {$order['merchant_balance']}</th>
	</tr>
	<if condition="$order['pay_type'] eq 'offline' AND empty($order['third_id'])" >
	<tr>
		<th colspan="3">线下需向商家付金额：<font color="red">￥{$order['price']-$order['merchant_balance']-$order['balance_pay']}元</font></th>
	</tr>
	</if>
	<tr>
		<td colspan="3" style="line-height:22px;padding-top:15px;">
		姓名：{$order['name']}<br/>
		电话：{$order['phone']}<br/>
		地址：{$order['address']}
		</td>
	</tr>
</table>
<include file="Public:footer"/>