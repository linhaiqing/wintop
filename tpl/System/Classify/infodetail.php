<include file="Public:header"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="15%">标题</th>
				<td>{$detail['title']}</td>
			</tr>
			<tr>
				<th width="15%">联系人</th>
				<td>{$detail['lxname']}</td>
			</tr>
			<tr>
				<th width="15%">联系人电话</th>
				<td ><if condition="strpos($detail['lxtel'], 'load/telimages')"><img src="{$config['site_url']}/{$detail['lxtel']}"><else/>{$detail['lxtel']}</if></td>
			</tr>
			<if condition="!empty($content)">
	        <volist name="content" id="vv">
			<tr>
				<th width="15%">{$vv['tn']}</th>
				<if condition="is_array($vv['vv'])">
				<td>{$vv['vv']|implode=','}</td>
				<elseif condition="$vv['type'] eq 1 AND isset($vv['unit']) AND !empty($vv['unit'])"/>
				<td>{$vv['vv']} / {$vv['unit']}</td>
				<elseif condition="$vv['type'] eq 5  AND !empty($vv['vv'])"/>
				<td>{$vv['vv']|htmlspecialchars_decode=ENT_QUOTES}</td>
				<else/><td >{$vv['vv']}</td></if>
			</tr>
			</volist>
			</if>
			<if condition="!empty($detail['otherdesc'])">
			<tr>
				<th width="15%">职位描述</th>
				<php>$detail['otherdesc']=htmlspecialchars_decode($detail['otherdesc'],ENT_QUOTES);</php>
				<td >{$detail['otherdesc']|htmlspecialchars_decode=ENT_QUOTES}</td>
			</tr>
			</if>
			<if condition="!empty($detail['description'])">
			<tr>
				<th width="15%">说明描述</th>
				<php>$detail['description']=htmlspecialchars_decode($detail['description'],ENT_QUOTES);</php>
				<td >{$detail['description']|htmlspecialchars_decode=ENT_QUOTES}</td>
			</tr>
			</if>
		</table>
		<if condition="!empty($imglist)">
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
		    <tr>
				<td width="100">上传的图片：</td>
			</tr>
		  <volist name="imglist" id="vv">
		    <php>if($mod==0)echo "<tr>";</php>
			<td><img class="view_msg" style="width:300px;" src="{$vv}"></td>
			<php>if($mod==1)echo "</tr>";</php>
		 </volist>
		</table>
		</if>
		<div class="btn hidden">
			<form id="myform" method="post" action="{:U('Classify/toVerify')}" frame="true" refresh="true">
		     <input type="hidden" name="vid" value="{$vid}"/>
			 <input type="submit" name="dosubmit" id="dosubmit" class="button" />
			 </form>
		</div>

<!--<script type="text/javascript">
function toCheck(id){
   if(confirm('您确定审核通过此项吗？')){
    $.post("{:U('Classify/toVerify')}",{vid:id},function(data){
	  data=parseInt(data);
	  if(!data){
          //window.location.reload();
		  window.main.location.reload();
	   }
     },'JSON');
   }else{
     return false;
   }
}
</script>-->
<include file="Public:footer"/>