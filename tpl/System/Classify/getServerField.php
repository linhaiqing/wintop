<include file="Public:header"/>
   <style type="text/css">
   #popCyDiv{margin: 10px 0 0 30px}
   #popCyDiv select{width: 200px;}
   #popCyDiv div{ float: left;width: 230px;}
   #popCyDiv span{margin-bottom: 10px;display:inline-block;}
   #popCyDiv option{font-size: 16px;padding: 5px;}
   .desctitle{font-size: 16px;margin: 5px 0 0 15px;}
   </style>
    <div class="desctitle">请给 <span style="color:red;">{$param['fieldname']}</span> 选择对应更新字段</div>
	<if condition="!empty($allseverfields) AND is_array($allseverfields)"> 
	<form id="myform" name="myform" method="post" action="{:U('Classify/savepickset')}" frame="true" refresh="true">
	<div id="popCyDiv">
	  <div id="Cymain">
	  <span>您选择的一级分类：</span>
	   <select id="Classify1" size="15" disabled="disabled">
	    <volist name="allseverfields" id="vv">
		    <php>$subcy[$vv['mcid']]=$vv['subcy'];</php>
            <option value="{$vv['mcid']}" <if condition="$pickset['mcid'] eq $vv['mcid']"> selected="selected"</if>>{$vv['zhname']}</option>
		</volist>
	   </select>
	  </div>
	  
	  <if condition="$pickset['mscid'] gt 0">
	  <div id="Cysub"> 
	  <span>您选择的二级分类：</span>
	  <select size="15" disabled="disabled">
	   <volist name="subcy[$pickset['mcid']]" id="mvv">
	      <option value="{$mvv['cid']}" <if condition="$pickset['mscid'] eq $mvv['cid']"> selected="selected"</if>>{$mvv['szhname']}</option>
	   </volist>
	   </select>
	   </div>
	   	  <div id="Cyfield">
		     <span>请点击选择匹配字段：</span>
			 <select size="15" onclick="insert_datas(this,{$pickset['mcid']},{$pickset['mscid']})">
			   <option value="o00">不选择</option>
		   	   <volist name="subcy[$pickset['mcid']][$pickset['mscid']]['fields']" id="fvv">
				<option value="{$key}" <if condition="$key eq $pickset['kname']"> selected="selected"</if>>{$fvv}</option>
			</volist>
			</select>
		  </div>
	   <else/>
		  <div id="Cysub" style="display:none;"> </div>
	   	  <div id="Cyfield" style="display:none;"> </div>
	  </if>
	  
	</div>
	<div style="display:none;">
	   <input type="hidden" name="id" value="{$id}"/>
	   <input type="hidden" name="cid" value="{$param['cid']}"/>
	   <input type="hidden" name="fcid" value="{$param['fcid']}"/>
	   <input type="hidden" name="fieldid" value="{$param['fieldid']}"/>
	   <input type="hidden" id="mcid" name="mcid" value="{$pickset['mcid']}"/>
	   <input type="hidden" id="mscid" name="mscid" value="{$pickset['mscid']}"/>
	   <input type="hidden" id="kname" name="kname" value="{$pickset['kname']}"/>
	   <input type="hidden" id="kvalue" name="kvalue" value="{$pickset['kvalue']}"/>
	</div>
		<div class="btn hidden">
			<input  type="submit" id="dosubmit"  name="dosubmit" class="button"  value="提交" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
 <else/>
 	<br/>
	<br/>
	<div class="desctitle" style="color:red;">抱歉暂无可更新的信息！</div>
	</if>
<include file="Public:footer"/>
<script type="text/javascript">
var SubSelectData=<php> echo json_encode($subcy);</php>;
function GetSubSelect(vv){
	var html ='<span>请点击选择二级分类：</span><select size="15" onclick="GetFiledSelect(this.value,'+vv+')">';
	var getSub=SubSelectData[vv];
	if(getSub){
	  $.each(getSub,function(i,v){
	    html+='<option value="'+v.cid+'">'+v.szhname+'</option>';
	  });
	}
	html+='</select>';
	$('#Cysub').html(html).show();
	$('#Cyfield').hide();
}

function GetFiledSelect(cid,mcid){
   var getFileds=SubSelectData[mcid][cid]['fields'];
	var html ='<span>请点击选择匹配字段：</span><select size="15" onclick="insert_datas(this,'+mcid+','+cid+')">';
    if(getFileds){
	  	 $.each(getFileds,function(kk,vv){
	       html+='<option value="'+kk+'">'+vv+'</option>';
	     });
	}
	html+='</select>';
	$('#Cyfield').html(html).show();
}

function insert_datas(obj,mcid,cid){
  $('#mcid').val(mcid);
  $('#mscid').val(cid);
  $('#kname').val(obj.value);
  $('#kvalue').val($(obj.options[obj.selectedIndex]).text());
}

function SubmitForm(){
   document.getElementById('myform').submit();
}
</script>