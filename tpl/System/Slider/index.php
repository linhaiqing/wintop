<include file="Public:header"/>		<div class="mainbox">			<div id="nav" class="mainnav_title">				<ul>					<a href="{:U('Slider/index')}" class="on">导航分类列表</a>|					<a href="javascript:void(0);" onclick="window.top.artiframe('{:U('Slider/cat_add')}','添加导航分类',400,180,true,false,false,addbtn,'add',true);">添加导航分类</a>				</ul>			</div>			<form name="myform" id="myform" action="" method="post">				<div class="table-list">					<table width="100%" cellspacing="0">						<colgroup><col> <col> <col><col>  <col width="180" align="center"> </colgroup>						<thead>							<tr>								<th>编号</th>								<th>名称</th>								<th>标识</th>								<th>导航列表</th>								<th class="textcenter">操作</th>							</tr>						</thead>						<tbody>							<if condition="is_array($category_list)">								<volist name="category_list" id="vo">									<tr>										<td>{$vo.cat_id}</td>										<td>{$vo.cat_name}</td>										<td>{$vo.cat_key}</td>										<td><a href="{:U('Slider/slider_list',array('cat_id'=>$vo['cat_id']))}">导航列表</a></td>										<td class="textcenter"><a href="javascript:void(0);" onclick="window.top.artiframe('{:U('Slider/cat_edit',array('cat_id'=>$vo['cat_id'],'frame_show'=>true))}','查看导航分类',400,180,true,false,false,false,'add',true);">查看</a> | <a href="javascript:void(0);" onclick="window.top.artiframe('{:U('Slider/cat_edit',array('cat_id'=>$vo['cat_id']))}','编辑导航分类',400,180,true,false,false,editbtn,'add',true);">编辑</a> | <a href="javascript:void(0);" class="delete_row" parameter="cat_id={$vo.cat_id}" url="{:U('Slider/cat_del')}">删除</a></td>									</tr>								</volist>							<else/>								<tr><td class="textcenter red" colspan="8">列表为空！</td></tr>							</if>						</tbody>					</table>				</div>			</form>		</div><include file="Public:footer"/>