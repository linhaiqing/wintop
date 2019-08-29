<include file="Public:header"/>
	<form id="myform" method="post" action="{:U('Appoint/order_edit')}" enctype="multipart/form-data">
		<input type="hidden" name="appoint_id" value="{$now_order.appoint_id}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<td colspan="4" style="padding-left:5px;color:black;"><b>订单信息：</b></td>
			</tr>
			<tr>
				<th width="80">编号</th>
				<td><input type="text" readonly="value" class="input fl" name="order_id" id="order_id" value="{$now_order.order_id}" size="25" placeholder="" validate="maxlength:20,required:true" tips=""/></td>
				<th width="80">用户昵称</th>
				<td><input type="text" readonly="value" class="input fl" name="nickname" id="nickname" value="{$now_order.nickname}" size="25" placeholder="" validate="maxlength:20,required:true" tips=""/></td>
			</tr>
			<tr>
				<th width="80">用户手机</th>
				<td><input type="text" readonly="value" class="input fl" name="phone" id="phone" value="{$now_order.phone}" size="25" placeholder="" validate="maxlength:20,required:true" tips=""/></td>
				<th width="80">服务名称</th>
				<td><input type="text" readonly="value" class="input fl" name="appoint_name" value="{$now_order.appoint_name}" size="10" placeholder="" validate="maxlength:6,required:true" tips=""/></td>
			</tr>
			<tr>
				<th width="80">店铺名称</th>
				<td><input type="text" readonly="value" class="input fl" name="store_name" value="{$now_order.store_name}" size="10" placeholder="" validate="maxlength:6,required:true" tips=""/></td>
				<th width="80">下单时间</th>
				<td><input type="text" readonly="value" class="input fl" name="order_time" value="{$now_order.order_time|date='Y-m-d H:i:s',###}" size="10" placeholder="" validate="maxlength:6,required:true" tips=""/></td>
			</tr>
			<tr>
				<th width="80">预约日期</th>
				<td><input type="text" class="input fl" name="appoint_date" value="{$now_order.appoint_date}" size="10" placeholder="" validate="maxlength:6,required:true,number:true" tips=""/></td>
				<th width="80">预约时间点</th>
				<td><input type="text" class="input fl" name="appoint_time" value="{$now_order.appoint_time}" size="10" placeholder="" validate="maxlength:6,required:true,number:true" tips=""/></td>
			</tr>
			<tr>
				<th width="80">定金金额</th>
				<td><input type="text" class="input fl" name="payment_money" value="{$now_order.payment_money}" size="10" placeholder="" validate="maxlength:6,required:true,number:true" tips=""/></td>
				<th width="80">用户留言</th>
				<td><input type="text" class="input fl" name="content" value="{$now_order.content}" size="10" placeholder="" validate="maxlength:6,required:true,number:true" tips=""/></td>
			</tr>
			<tr>
				<th width="80">服务类型</th>
				<td>
					<span class="cb-enable"><label class="cb-enable <if condition="$now_order['appoint_type'] eq 0">selected</if>"><span>到店</span><input type="radio" name="appoint_type" value="0" <if condition="$now_order['appoint_type'] eq 0">checked="checked"</if>/></label></span>
					<span class="cb-disable"><label class="cb-disable <if condition="$now_order['appoint_type'] eq 1">selected</if>"><span>上门</span><input type="radio" name="appoint_type" value="1"  <if condition="$now_order['appoint_type'] eq 1">checked="checked"</if> /></label></span>
				</td>
				<th width="80">支付状态</th>
				<td>
					<span class="cb-enable"><label class="cb-enable <if condition="$now_order['paid'] eq 0">selected</if>"><span>未支付</span><input type="radio" name="paid" value="0" <if condition="$now_order['paid'] eq 0">checked="checked"</if>/></label></span>
					<span class="cb-enable"><label class="cb-enable <if condition="$now_order['paid'] eq 1">selected</if>"><span>已支付</span><input type="radio" name="paid" value="1"  <if condition="$now_order['paid'] eq 1">checked="checked"</if> /></label></span>
					<span class="cb-disable"><label class="cb-disable <if condition="$now_order['paid'] eq 2">selected</if>"><span>已支付</span><input type="radio" name="paid" value="2"  <if condition="$now_order['paid'] eq 2">checked="checked"</if> /></label></span>
				</td>
			</tr>
			<tr>
				<th width="80">服务状态</th>
				<td>
					<span class="cb-enable"><label class="cb-enable <if condition="$now_order['service_status'] eq 0">selected</if>"><span>未服务</span><input type="radio" name="service_status" value="0"  <if condition="$now_order['service_status'] eq 0">checked="checked"</if> /></label></span>
					<span class="cb-disable"><label class="cb-disable <if condition="$now_order['service_status'] eq 1">selected</if>"><span>已服务</span><input type="radio" name="service_status" value="1"  <if condition="$now_order['service_status'] eq 1">checked="checked"</if> /></label></span>
				</td>
				<th width="80">验证店员</th>
				<td><input type="text" class="input fl" name="content" value="{$now_order.last_staff}" size="10" placeholder="" validate="maxlength:6,required:true,number:true" tips=""/></td>
			</tr>
			<if condition="$now_order['paid'] eq '1'">
				<tr>
					<th width="15%">支付方式</th>
					<th width="85%" colspan="3">余额支付金额 ￥{$now_order.balance_pay}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;实际支付金额 ￥{$now_order.pay_money}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;使用商户余额 ￥{$now_order.merchant_balance}</th>
				</tr>
			</if>
			<if condition="$cue_list">
				<tr>
					<td colspan="4" style="padding-left:5px;color:black;"><b>自定义填写项：</b></td>
				</tr>
				<volist name="cue_list" id="val">
					<if condition="$val['type'] eq 2">
						<tr>
							<th width="80">{$val.name}</th>
							<td colspan="3">
								地址：{$val.address}
								<!--{$val.value}-->
							</td>
						</tr>
					<else/>
						<tr>
							<th width="80">{$val.name}</th>
							<td colspan="3">{$val.value}</td>
						</tr>
					</if>
				</volist>
			</if>
		</table>
	</form>
<include file="Public:footer"/>