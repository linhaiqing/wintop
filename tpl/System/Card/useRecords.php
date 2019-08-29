<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{:U('Card/index')}">会员卡</a>》
					<a href="{:U('Card/index', array('id' => $thisCard['id']))}">{$thisCard.cardname}</a>》
					<a href="{:U('Card/' . $a, array('id' => $thisCard['id']))}">{$thisItem.title}</a>》
					<a>使用统计</a>
				</ul>
			</div>
			<form name="myform" id="myform" action="" method="post">
				<div class="table-list">
					<table width="100%" cellspacing="0">
						<colgroup><col><col> <col> <col><col><col><col> <col width="140" align="center"> </colgroup>
						<thead>
							<tr>
								<th id="shopList_c1" width="100">卡号</th>
								<th id="shopList_c1" width="100">姓名</th>
								<th id="shopList_c0" width="100">电话</th>
								<th id="shopList_c3" width="100">消费金额</th>
								<th id="shopList_c3" width="100">操作员</th>
								<th id="shopList_c3" width="100">备注</th>
								<th id="shopList_c3" width="100">时间</th>
								<th id="shopList_c11" width="100">操作</th>
							</tr>
						</thead>
						<tbody>
							<if condition="$list">
								<volist name="list" id="c">
									<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
										<td>{$c.cardNum}</td>
										<td>{$c.userName}</td>
										<td>{$c.userTel}</td>
										<td>{$c.expense}</td>
										<td><if condition="$c.operName eq ''">无<else/>{$c.operName}</if></td>
										<td><if condition="$c.operName eq ''">会员卡支付或积分兑换<else/>{$c.notes}</if></td>
										<td><nobr>{$c.time|date='Y-m-d H:i:s',###}</nobr></td>
										<td class="button-column" nowrap="nowrap">
											<a title="删除" class="red" style="padding-right:8px;" href="{:U('Card/useRecord_del',array('itemid'=>$c['id'],'id'=>$thisCard['id']))}">删除</a>
										</td>
									</tr>
								</volist>
							<else/>
								<tr><td class="textcenter red" colspan="8">列表为空！</td></tr>
							</if>
						</tbody>
					</table>
				</div>
			</form>
		</div>
<include file="Public:footer"/>