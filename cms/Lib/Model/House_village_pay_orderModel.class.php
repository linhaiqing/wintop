<?php
class House_village_pay_orderModel extends Model{
	/*得到小区的新闻列表*/
	public function get_limit_list_page($column,$pageSize=20,$isSystem=false){
		if(!$column['village_id']){
			return null;
		}
    	
    	$condition_table  = array(C('DB_PREFIX').'House_village_pay_order'=>'o',C('DB_PREFIX').'house_village_user_bind'=>'b');
    	$condition_where = " `o`.`village_id` = `b`.`village_id`  AND `o`.`bind_id` = `b`.`pigcms_id`  AND `o`.`village_id` =".$column['village_id'];
    	
    	
    	if($column['paid']){
    		$condition_where .= " AND `o`.`paid`= ".intval($column['paid']);
    	}
    	
    	$condition_field = '`b`.`name` AS `username` ,o.*,b.*';
    	
    	$order = ' `o`.`order_id` DESC, `o`.`paid` ASC';
    	if($isSystem){
    		import('@.ORG.system_page');
    	}else{
    		import('@.ORG.merchant_page');
    	}
    	$count_order = D('')->table($condition_table)->where($condition_where)->count();
    	$p = new Page($count_order,$pageSize,'page');
    	$order_list = D('')->field($condition_field)->table($condition_table)->where($condition_where)->order($order)->limit($p->firstRow.','.$p->listRows)->select();
    	
    	$total = D('')->field(' SUM(`o`.`money` ) AS totalMoney ')->table($condition_table)->where($condition_where)->find();
    	$already = D('')->field(' SUM(`o`.`money` ) AS readyMoney ')->table($condition_table)->where($condition_where." AND `o`.`is_pay_bill`=1 ")->find();
    	
    	$return['pagebar'] = $p->show();
    	$return['order_list'] = $order_list;
    	$return['totalMoney'] = $total;
    	$return['readyMoney'] = $already;
    	
    	return $return;
	}
	
}