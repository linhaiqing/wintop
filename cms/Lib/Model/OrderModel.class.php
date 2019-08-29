<?php
class OrderModel extends Model
{
	
	public function get_order_by_mer_id($mer_id, $type = 'meal', $is_system = false)
	{
		if ($is_system) {
			import('@.ORG.system_page');
		} else {
			import('@.ORG.merchant_page');
		}
		$mode = new Model();
		switch ($type) {
			case 'meal':
				$db = D('Meal_order');
				$count = $db->where(array('mer_id' => $mer_id, 'paid' => 1, 'is_own' => 0, 'pay_type' => array('NEQ', 'offline'), 'status' => array('in', '1, 2')))->count();
				$p = new Page($count, 20);
				$sql = "SELECT order_id, info as order_name, uid, mer_id, store_id, phone, total, (balance_pay+payment_money) as price, price as order_price, dateline, paid, status, pay_type, pay_time, third_id, is_mobile_pay, balance_pay, payment_money, card_id, merchant_balance, is_pay_bill FROM ". C('DB_PREFIX') . "meal_order WHERE mer_id={$mer_id} AND paid=1 AND is_own=0 AND status in (1,2) AND (pay_type<>'offline' OR balance_pay<>'0.00')";
				$sql .= " ORDER BY dateline DESC LIMIT {$p->firstRow}, {$p->listRows}";
				$order_list = $mode->query($sql);
				$total_list = $db->field('sum(balance_pay + payment_money) as price, is_pay_bill')->where("mer_id={$mer_id} AND paid=1 AND is_own=0 AND status in (1,2) AND (pay_type<>'offline' OR balance_pay<>'0.00')")->group('is_pay_bill')->select();
				break;
			case 'group':
				$db = D('Group_order');
				$count = $db->where(array('mer_id' => $mer_id, 'pay_type' => array('NEQ', 'offline'), 'paid' => 1, 'is_own' => 0, 'status' => array('in', '1, 2')))->count();
				$p = new Page($count, 20);
				$sql = "SELECT order_id, order_name, uid, mer_id, store_id, phone, num as total, (balance_pay+payment_money) as price, total_money as order_price, add_time as dateline, paid, status, pay_type, pay_time, third_id, is_mobile_pay, balance_pay, payment_money, card_id, merchant_balance, is_pay_bill FROM ". C('DB_PREFIX') . "group_order WHERE mer_id={$mer_id} AND paid=1 AND is_own=0 AND status in (1,2) AND (pay_type<>'offline' OR balance_pay<>'0.00')";
				$sql .= " ORDER BY dateline DESC LIMIT {$p->firstRow}, {$p->listRows}";
				$order_list = $mode->query($sql);
				$total_list = $db->field('sum(balance_pay + payment_money) as price, is_pay_bill')->where("mer_id={$mer_id} AND paid=1 AND is_own=0 AND status in (1,2) AND (pay_type<>'offline' OR balance_pay<>'0.00')")->group('is_pay_bill')->select();
				break;
			case 'weidian':
				$db = D('Weidian_order');
				$time = time() - 10 * 86400; //十天前的订单才能对账
				$count = $db->where("mer_id={$mer_id} AND paid=1 AND pay_type<>'offline' AND pay_time<'{$time}'")->count();
				$p = new Page($count, 20);
				$sql = "SELECT order_id, order_name, uid, mer_id, store_id, order_num as total, (payment_money+balance_pay) as price, money as order_price, add_time, paid, pay_type, pay_time, third_id, balance_pay, payment_money, merchant_balance, is_pay_bill FROM ". C('DB_PREFIX') . "weidian_order WHERE mer_id={$mer_id} AND paid=1 AND pay_time<'{$time}' AND pay_type<>'offline'";
				$sql .= " ORDER BY order_id DESC LIMIT {$p->firstRow}, {$p->listRows}";
				$order_list = $mode->query($sql);
				$total_list = $db->field('sum(balance_pay + payment_money) as price, is_pay_bill')->where("mer_id={$mer_id} AND paid=1 AND pay_type<>'offline' AND pay_time<'{$time}'")->group('is_pay_bill')->select();
				break;
			case 'wxapp':
				$db = D('Wxapp_order');
				$count = $db->where("mer_id={$mer_id} AND paid=1 AND pay_type<>'offline'")->count();
				$p = new Page($count, 20);
				$sql = "SELECT order_id, order_name, uid, mer_id, 0 as store_id, order_num as total, (payment_money+balance_pay) as price, money as order_price, add_time, paid, pay_type, pay_time, third_id, balance_pay, payment_money, merchant_balance, is_pay_bill FROM ". C('DB_PREFIX') . "wxapp_order WHERE mer_id={$mer_id} AND paid=1 AND pay_time<'{$time}' AND pay_type<>'offline'";
				$sql .= " ORDER BY order_id DESC LIMIT {$p->firstRow}, {$p->listRows}";
				$order_list = $mode->query($sql);
				$total_list = $db->field('sum(balance_pay + payment_money) as price, is_pay_bill')->where("mer_id={$mer_id} AND paid=1 AND pay_type<>'offline'")->group('is_pay_bill')->select();
				break;
			case 'appoint':
				$db = D('Appoint_order');
				$count = $db->where("mer_id={$mer_id} AND paid=1 AND is_own=0 AND pay_type<>'offline' AND service_status=1")->count();
				$p = new Page($count, 20);
				$sql = "SELECT order_id, appoint as order_name, uid, mer_id, store_id, 1 as total, (pay_money+balance_pay) as price, payment_money as order_price, order_time as add_time, paid, pay_type, pay_time, third_id, balance_pay, pay_money as payment_money, merchant_balance, is_pay_bill FROM ". C('DB_PREFIX') . "appoint_order WHERE mer_id={$mer_id} AND paid=1 AND is_own=0 AND pay_time<'{$time}' AND pay_type<>'offline' AND service_status=1";
				$sql .= " ORDER BY order_id DESC LIMIT {$p->firstRow}, {$p->listRows}";
				$order_list = $mode->query($sql);
				$total_list = $db->field('sum(balance_pay + pay_money) as price, is_pay_bill')->where("mer_id={$mer_id} AND paid=1 AND is_own=0 AND pay_type<>'offline' AND service_status=1")->group('is_pay_bill')->select();
				break;
		}
		
		/** total: 本页的总额 ; finshtotal:本页已对账的总额; alltotal:未对账的总额; alltotalfinsh:全部已对账总额*/
		$total = $finshtotal = $alltotal = $alltotalfinsh = 0;
		foreach ($total_list as $row) {
			$row['is_pay_bill'] && $alltotalfinsh += $row['price'];//已对账的总额
			$row['is_pay_bill'] || $alltotal += $row['price'];     //未对账的总额
		}
		
		//商家的门店信息
		$store_list = D('Merchant_store')->field('store_id, name')->where(array('mer_id' => $mer_id))->select();
		$store_idkey_list = array();
		foreach ($store_list as $store) {
			$store_idkey_list[$store['store_id']] = $store;
		}
		
		foreach ($order_list as &$order) {
			$order['store_name'] = isset($store_idkey_list[$order['store_id']]['name']) ? $store_idkey_list[$order['store_id']]['name'] : '';
			if ($type == 'meal') {
				$order['order_name'] = unserialize($order['order_name']);
			} elseif ($type == 'appoint') {
				$order['order_name'] = $order['order_name'] == 0 ? '到店' : '上门';
			}
			$order['pay_type_show'] = D('Pay')->get_pay_name($order['pay_type'], $order['is_mobile_pay']);
			$total += $order['price'];								//本页的总额
			$order['is_pay_bill'] && $finshtotal += $order['price'];	//本页已对账的总额
		}
		return array('order_list' => $order_list, 'pagebar' => $p->show(), 'total' => $total, 'finshtotal' => $finshtotal, 'alltotalfinsh' => $alltotalfinsh, 'alltotal' => $alltotal);
	}
	
	public function export_order_by_mid($mer_id, $type = 'meal', $is_pay_bill = 0)
	{
		
		$mode = new Model();
		switch ($type) {
			case 'meal':
				$db = D('Meal_order');
				$sql = "SELECT order_id, orderid, info as order_name, uid, mer_id, store_id, phone, total, (balance_pay+payment_money) as price, price as order_price, dateline, paid, status, pay_type, pay_time, third_id, is_mobile_pay, balance_pay, payment_money, card_id, merchant_balance, is_pay_bill FROM ". C('DB_PREFIX') . "meal_order WHERE is_pay_bill=0 AND mer_id={$mer_id} AND paid=1 AND is_own=0 AND status in (1,2) AND (pay_type<>'offline' OR balance_pay<>'0.00')";
				$sql .= " ORDER BY dateline DESC ";
				$order_list = $mode->query($sql);
				break;
			case 'group':
				$db = D('Group_order');
				$sql = "SELECT order_id, order_id as orderid, order_name, uid, mer_id, store_id, phone, num as total, (balance_pay+payment_money) as price, total_money as order_price, add_time as dateline, paid, status, pay_type, pay_time, third_id, is_mobile_pay, balance_pay, payment_money, card_id, merchant_balance, is_pay_bill FROM ". C('DB_PREFIX') . "group_order WHERE is_pay_bill=0 AND mer_id={$mer_id} AND paid=1 AND is_own=0 AND status in (1,2) AND (pay_type<>'offline' OR balance_pay<>'0.00')";
				$sql .= " ORDER BY dateline DESC ";
				$order_list = $mode->query($sql);
				break;
			case 'weidian':
				$db = D('Weidian_order');
				$time = time() - 10 * 86400; //十天前的订单才能对账
				$sql = "SELECT order_id, weidian_order_id as orderid, order_name, uid, mer_id, store_id, order_num as total, (payment_money+balance_pay) as price, money as order_price, add_time, paid, pay_type, pay_time, third_id, balance_pay, payment_money, merchant_balance, is_pay_bill FROM ". C('DB_PREFIX') . "weidian_order WHERE is_pay_bill=0 AND mer_id={$mer_id} AND paid=1 AND pay_time<'{$time}' AND pay_type<>'offline'";
				$sql .= " ORDER BY order_id DESC ";
				$order_list = $mode->query($sql);
				break;
			case 'wxapp':
				$db = D('Wxapp_order');
				$sql = "SELECT order_id, wxapp_order_id as orderid, order_name, uid, mer_id, 0 as store_id, order_num as total, (payment_money+balance_pay) as price, money as order_price, add_time, paid, pay_type, pay_time, third_id, balance_pay, payment_money, merchant_balance, is_pay_bill FROM ". C('DB_PREFIX') . "wxapp_order WHERE is_pay_bill=0 AND mer_id={$mer_id} AND paid=1 AND pay_time<'{$time}' AND pay_type<>'offline'";
				$sql .= " ORDER BY order_id ";
				$order_list = $mode->query($sql);
				break;
			case 'appoint':
				$db = D('Appoint_order');
				$sql = "SELECT order_id, order_id as orderid, appoint as order_name, uid, mer_id, store_id, 1 as total, (pay_money+balance_pay) as price, payment_money as order_price, order_time as add_time, paid, pay_type, pay_time, third_id, balance_pay, pay_money as payment_money, merchant_balance, is_pay_bill FROM ". C('DB_PREFIX') . "appoint_order WHERE is_pay_bill=0 AND mer_id={$mer_id} AND paid=1 AND is_own=0 AND pay_time<'{$time}' AND pay_type<>'offline' AND service_status=1";
				$sql .= " ORDER BY order_id DESC ";
				$order_list = $mode->query($sql);
				break;
		}
		
		
		//商家的门店信息
		$store_list = D('Merchant_store')->field('store_id, name')->where(array('mer_id' => $mer_id))->select();
		$store_idkey_list = array();
		foreach ($store_list as $store) {
			$store_idkey_list[$store['store_id']] = $store;
		}
		
		foreach ($order_list as &$order) {
			$order['store_name'] = isset($store_idkey_list[$order['store_id']]['name']) ? $store_idkey_list[$order['store_id']]['name'] : '';
			if ($type == 'meal') {
				$order['order_name'] = unserialize($order['order_name']);
			} elseif ($type == 'appoint') {
				$order['order_name'] = $order['order_name'] == 0 ? '到店' : '上门';
			}
			$order['pay_type_show'] = D('Pay')->get_pay_name($order['pay_type'], $order['is_mobile_pay']);
		}
		return $order_list;
		return array('order_list' => $order_list);
		
	}
}
?>