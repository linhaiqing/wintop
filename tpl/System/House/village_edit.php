<include file="Public:header"/>
	<form id="myform" method="post" action="{:U('House/village_edit')}" frame="true" refresh="true">
		<input type="hidden" name="village_id" value="{$now_village.village_id}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="100">小区名称</th>
				<td><input type="text" class="input fl" name="village_name" value="{$now_village.village_name}" size="40" placeholder="请输入小区名称" validate="maxlength:20,required:true"/></td>
			</tr>
			<tr>
				<th width="100">小区地址</th>
				<td><input type="text" class="input fl" name="village_address" value="{$now_village.village_address}" size="40" placeholder="请输入小区地址" validate="maxlength:50,required:true"/></td>
			</tr>
			<tr>
				<th width="100">物业公司名称</th>
				<td><input type="text" class="input fl" name="property_name" value="{$now_village.property_name}" size="40" placeholder="请输入物业公司名称" validate="maxlength:20,required:true"/></td>
			</tr>
			<tr>
				<th width="100">物业联系地址</th>
				<td><input type="text" class="input fl" name="property_address" value="{$now_village.property_address}" size="40" placeholder="请输入物业联系地址" validate="maxlength:50,required:true"/></td>
			</tr>
			<tr>
				<th width="100">物业联系电话</th>
				<td><input type="text" class="input fl" name="property_phone" value="{$now_village.property_phone}" size="20" placeholder="请输入物业联系电话" validate="maxlength:50,required:true" tips="多个号码以空格分开"/></td>
			</tr>
			<tr>
				<th width="100">社区后台管理帐号</th>
				<td><input type="text" class="input fl" name="account" value="{$now_village.account}" size="20" placeholder="请输入社区后台管理帐号" validate="maxlength:50,required:true" tips="多个社区帐号一致，将认为是同一家物业公司。进入社区后台会提示进入哪个小区"/></td>
			</tr>
			<tr>
				<th width="100">社区后台管理密码</th>
				<td><input type="text" class="input fl" name="pwd" size="20" placeholder="不修改请勿填写" validate="maxlength:50,minlength:6" tips="不修改请勿填写"/></td>
			</tr>
			<tr>
				<th width="100">状态</th>
				<td class="radio_box">
					<span class="cb-enable"><label class="cb-enable <if condition="$now_village['status'] eq 1 || $now_village['status'] eq 0">selected</if>"><span>正常</span><input type="radio" name="status" value="<if condition="$now_village['status'] eq 0">0<else/>1</if>" <if condition="$now_village['status'] eq 1 || $now_village['status'] eq 0">checked="checked"</if>/></label></span>
					<span class="cb-disable"><label class="cb-disable <if condition="$now_village['status'] eq 2">selected</if>"><span>禁止</span><input type="radio" name="status" value="2" <if condition="$now_village['status'] eq 2">checked="checked"</if>/></label></span>
				</td>
			</tr>
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<script>
function addLink(domid,iskeyword){
	art.dialog.data('domid', domid);
	art.dialog.open('?g=Admin&c=Link&a=insert&iskeyword='+iskeyword,{lock:true,title:'插入链接或关键词',width:600,height:400,yesText:'关闭',background: '#000',opacity: 0.45});
}
</script>
<include file="Public:footer"/>