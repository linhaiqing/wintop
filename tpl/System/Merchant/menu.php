<include file="Public:header"/>
	<form id="myform" method="post" action="{:U('Merchant/savemenu')}" frame="true" refresh="true">
		<input type="hidden" name="mer_id" value="{$merchant.mer_id}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
		<volist name="menus" id="rowset">
			<tr>
				<th width="20%">
					<label><input type="checkbox" class="menu_{$rowset['id']} father_menu" value="{$rowset['id']}" name="menus[]" <if condition="in_array($rowset['id'], $merchant['menus'])">checked</if>/>&nbsp;{$rowset['name']}</label>
				</th>
				<td>
					<volist name="rowset['lists']" id="row">
					<label><input type="checkbox" class="child_menu_{$row['fid']} child_menu" value="{$row['id']}"  name="menus[]" data-fid="{$row['fid']}"  <if condition="in_array($row['id'], $merchant['menus'])">checked</if> />&nbsp;{$row['name']}</label>　
					</volist>
				</td>
			</tr>
		</volist>
		<tr><td colspan="2"><label><input type="checkbox" id="all"/> 全选</label></td></tr>
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
<style>
.frame_form td label{
	margin: 5px;
    display: inline-block;
}
</style>
<script type="text/javascript">
$(document).ready(function(){
	$('#all').click(function(){
		if ($(this).attr('checked')) {
			$('.father_menu, .child_menu').attr('checked', true);
		} else {
			$('.father_menu, .child_menu').attr('checked', false);
		}
	});
	$('.father_menu').click(function(){
		var fid = $(this).val();
		if ($(this).attr('checked')) {
			$('.child_menu_' + fid).attr('checked', true);
		} else {
			$('.child_menu_' + fid).attr('checked', false);
		}
	});
	$('.child_menu').click(function(){
		var fid = $(this).attr('data-fid');
		if ($(this).attr('checked')) {
			$('.menu_' + fid).attr('checked', true);
		} else {
			var flag = false;
			$('.child_menu_' + fid).each(function(){
				if ($(this).attr('checked')) {
					flag = true;
				}
			});
			$('.menu_' + fid).attr('checked', flag);
		}
	});
});
</script>
<include file="Public:footer"/>