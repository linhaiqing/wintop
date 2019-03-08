<include file="Public:header"/>
	<form id="myform" method="post" frame="true" refresh="true">
	<volist name="image_text" id="vo">
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="80">标题{$i}</th>
				<td>{$vo.title}</td>
			</tr>
			<tr>
				<th width="80">简介{$i}</th>
				<td>{$vo.digest}</td>
			</tr>
			<tr>
				<th width="80">封面图{$i}</th>
				<td><img src="{$vo.cover_pic}" style="width:70px;"/></td>
			</tr>
			<tr>
				<th width="80">详情{$i}</th>
				<td>{$vo.content}</td>
			</tr>
		</table>
	</volist>
	</form>
<include file="Public:footer"/>