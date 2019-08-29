<include file="Public:header"/>
	<form id="myform" method="post" action="{:U('Diymenu/class_add')}" enctype="multipart/form-data" frame="true" refresh="true">
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="80">菜单名称</th>
				<td><input type="text" class="input fl" name="title" size="20" placeholder="主菜单名称" validate="maxlength:20,required:true"/></td>
			</tr>
			<tr>
				<th width="80">父级菜单</th>
				<td>
					<div class="mr15 l">
					<select name="pid" id="pid">
						<option selected="selected" value="0">请选择父菜单</option>
						<volist id="class" name="class">
							<option  value="{$class.id}">{$class.title}</option>
						</volist>
					</select>
					<em class="notice_tips" tips="二级菜单需要选择父菜单"></em>
					</div>
				</td>
			</tr>
			<tr>
				<th width="80">菜单类型</th>
				<td>
					<div class="mr15 l">
					<select name="menu_type" class="menu_type">
						<option value="1">关键词回复菜单</option>		
						<option value="2">url链接菜单</option>
						<option value="3">微信扩展菜单</option>
					</select>
					</div>
				</td>
			</tr>
			<tr>
				<th width="80">关联关键词</th>
				<td><input type="text" class="input fl" name="keyword" style="width:200px;" placeholder="关联关键词"/></td>
			</tr>
			<tr style="display:none;" class="url">
				<th width="80">外链接url</th>
				<td>
				<input type="text" class="input fl" name="url" id="url" style="width:200px;" placeholder="外链接url"/>
				<a href="#modal-table" class="btn btn-sm btn-success" onclick="addLink('url',0)" data-toggle="modal">从功能库选择</a>
				</td>
			</tr>
			<tr style="display:none;" class="wxsys">
				<th width="80">扩展菜单：</th>
				<td>
					<div class="mr15 l">
						<select name="wxsys">
							<option value="">请选择..</option>
							<volist name="wxsys" id="wxsys">
								<option value="{$wxsys}">{$wxsys}</option>
							</volist>
						</select>
					</div>
					<div class="system l"></div>
				</td>
			</tr>
			<tr>
				<th width="80">显示：</th>
				<td>
					<span class="cb-enable"><label class="cb-enable selected"><span>是</span><input type="radio" name="is_show" value="1" checked="checked" /></label></span>
					<span class="cb-disable"><label class="cb-disable"><span>否</span><input type="radio" name="is_show" value="0" /></label></span>
				</td>
			</tr>
			<tr>
				<th width="80">排序：</th>
				<td>
					<div class="mr15 l">
					<input id="sortid" class="input fl" name="sort" title="排序" value="" type="text"></div>
					<div class="system l"></div>
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
$(function(){
	$('.menu_type').change(function(){
		var val 	= $(this).val();
		if(val == 1){
			$('.keyword').css('display','');
			$('.wxsys').css('display','none');
			$('.url').css('display','none');
		}else if(val == 2){
			$('.keyword').css('display','none');
			$('.wxsys').css('display','none');
			$('.url').css('display','');		
		}else if(val == 3){

			$('.keyword').css('display','none');
			$('.wxsys').css('display','');
			$('.url').css('display','none');
		}
	});
});

function addLink(domid,iskeyword){
	art.dialog.data('domid', domid);
	art.dialog.open('?g=Admin&c=Link&a=insert&iskeyword='+iskeyword,{lock:true,title:'插入链接或关键词',width:800,height:500,yesText:'关闭',background: '#000',opacity: 0.45});
}
</script>
<include file="Public:footer"/>