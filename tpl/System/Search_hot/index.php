<include file="Public:header"/>		<div class="mainbox">			<div id="nav" class="mainnav_title">				<ul>					<a href="{:U('Search_hot/index')}" class="on">热门搜索词列表</a>|					<a href="javascript:void(0);" onclick="window.top.artiframe('{:U('Search_hot/add')}','添加热门搜索词',500,200,true,false,false,addbtn,'add',true);">添加热门搜索词</a>				</ul>			</div>			<form name="myform" id="myform" action="" method="post">				<div class="table-list">					<table width="100%" cellspacing="0">						<colgroup><col> <col> <col> <col><col> <col width="140" align="center"> </colgroup>						<thead>							<tr>								<th>排序</th>								<th>编号</th>								<th>名称</th>								<th>链接地址</th>								<th>编辑时间</th>								<th class="textcenter">操作</th>							</tr>						</thead>						<tbody>							<if condition="is_array($search_hot_list)">								<volist name="search_hot_list" id="vo">									<tr>										<td>{$vo.sort}</td>										<td>{$vo.id}</td>										<td>{$vo.name}</td>										<td>											<if condition="$vo['url']">												有链接 <a href="{$vo.url}" target="_blank">预览链接</a>											<elseif condition="$vo['type'] eq 0"/>												团购链接 <a href="{$config.site_url}/index.php?g=Group&c=Search&a=index&w={$vo.name|urlencode=###}" target="_blank">预览链接</a>											<elseif condition="$vo['type'] eq 1"/>												订餐链接 <a href="{$config.site_url}/index.php?g=Meal&c=Search&a=index&w={$vo.name|urlencode=###}" target="_blank">预览链接</a>											</if>										</td>										<td>{$vo.add_time|date='Y-m-d H:i:s',###}</td>										<td class="textcenter"><a href="javascript:void(0);" onclick="window.top.artiframe('{:U('Search_hot/edit',array('id'=>$vo['id'],'frame_show'=>true))}','查看详细信息',500,200,true,false,false,false,'add',true);">查看</a> | <a href="javascript:void(0);" onclick="window.top.artiframe('{:U('Search_hot/edit',array('id'=>$vo['id']))}','编辑热门搜索词',500,200,true,false,false,editbtn,'add',true);">编辑</a> | <a href="javascript:void(0);" class="delete_row" parameter="id={$vo.id}" url="{:U('Search_hot/del')}">删除</a></td>									</tr>								</volist>								<tr><td class="textcenter pagebar" colspan="8">{$pagebar}</td></tr>							<else/>								<tr><td class="textcenter red" colspan="8">列表为空！</td></tr>							</if>						</tbody>					</table>				</div>			</form>		</div><include file="Public:footer"/>