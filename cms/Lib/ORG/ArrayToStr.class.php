<?php
class ArrayToStr 
{

// 	public static function array_to_str($order_id, $table = 'meal_order')
// 	{
// 		$order = D(ucfirst($table))->field(true)->where(array('order_id' => $order_id))->find();
// 		if (is_array($order)) {
// 			$store = D('Merchant_store')->field(true)->where(array('store_id' => $order['store_id']))->find();
// 			$payarr = array('alipay' => '支付宝', 'weixin' => '微信支付', 'tenpay' => '财付通[wap手机]', 'tenpaycomputer' => '财付通[即时到帐]', 'yeepay' => '易宝支付', 'allinpay' => '通联支付', 'daofu' => '货到付款', 'dianfu' => '到店付款', 'chinabank' => '网银在线', 'offline' => '线下支付');
// 			if ($table == 'meal_order') {
// 				$msg = '';
// 				$msg .= chr(10) . '客户姓名：' . $order['name'];
// 				$msg .= chr(10) . '客户电话：' . $order['phone'];
// 				$msg .= chr(10) . '客户地址：' . $order['address'];
// 				$msg .= chr(10) . '下单时间：' . date("Y-m-d H:i:s", $order['dateline']);
// 				$msg .= chr(10) . '*******************************';
// 				if ($order['info']) {
// 					$list = unserialize($order['info']);
// 					foreach ($list as $k => $row) {
// 						$msg .= chr(10) . $row['name'] . ": ￥" . $row['price'] . " * " . $row['num'];
// 					}
// 				}
// 				$msg .= chr(10) . '*******************************';
// 				$msg .= chr(10) . '客户留言：' . $order['note'];
// 				$msg .= chr(10) . '菜品总数：' . $order['total'];
// 				$msg .= chr(10) . '菜品总价：￥' . $order['total_price'];
// 				$msg .= chr(10) . '优惠金额：￥' . $order['minus_price'];
// 				$msg .= chr(10) . '实收金额：￥' . $order['total_price'] - $order['minus_price'];
// 				$msg .= chr(10) . '订单号：' . $order['order_id'];
// 				if (empty($order['paid'])) {
// 					$msg .= chr(10) . '订单状态：未支付';
// 				} else {
// 					if (empty($order['status'])) {
// 						$msg .= chr(10) . '订单状态：未消费';
// 					} elseif ($order['status'] == 1) {
// 						$msg .= chr(10) . '订单状态：已消费';
// 					} elseif ($order['status'] == 2) {
// 						$msg .= chr(10) . '订单状态：已完成';
// 					} elseif ($order['status'] == 3) {
// 						$msg .= chr(10) . '订单状态：已退款';
// 					}
// 				}
// 				$pay_type = isset($payarr[$order['pay_type']]) ? $payarr[$order['pay_type']] : '';
// 				$pay_type && $msg .= chr(10) . '支付方式：' . $pay_type;
				
// 				if ($order['meal_type']) {
// 					$msg .= chr(10) . '消费方式：外卖';
// 				} else {
// 					$msg .= chr(10) . '消费方式：预定';
// 					if ($table = D('Merchant_store_table')->field(true)->where(array('pigcms_id' => $order['tableid']))->find()) {
// 						$msg .= chr(10) . '预定桌台：' . $table['name'];
// 					} else {
// 						$msg .= chr(10) . '预定桌台：未选择';
// 					}
// 				}
// 				$msg .= chr(10) . '※※※※※※※※※※※※※※※※';
// 				$msg .= chr(10) . '店铺名称：' . $store['name'];
// 				$msg .= chr(10) . '店铺电话：' . $store['phone'];
// 				$msg .= chr(10) . '店铺地址：' . $store['adress'];
// 				$msg .= chr(10) . '打印时间：' . date("Y-m-d H:i:s");
// 				$msg .= chr(10) . '谢谢惠顾，欢迎再次光临！';
// 				return $msg;
// 			} else {
// 				$user = D('User')->field(true)->where(array('uid' => $order['uid']))->find();
// 				$msg = '';
// 				$nickname = isset($user['nickname']) ? $user['nickname'] : '';
// 				$msg .= chr(10) . '客户姓名：' . $nickname;
// 				$msg .= chr(10) . '客户电话：' . $order['phone'];
// 				$msg .= chr(10) . '客户地址：' . $order['adress'];
// 				$msg .= chr(10) . '*******************************';
// 				$msg .= chr(10) . '订单号：' . $order['order_id'];
// 				$msg .= chr(10) . '商品名称：' . $order['order_name'];
// 				$msg .= chr(10) . '购买数量：' . $order['num'];
// 				$msg .= chr(10) . '总 价：￥' . $order['total_money'];
// 				$msg .= chr(10) . '优 惠：￥' . $order['wx_cheap'];
// 				$msg .= chr(10) . '实 收：￥' . $order['total_money'] - $order['wx_cheap'];
// 				$msg .= chr(10) . '下单时间：' . date("Y-m-d H:i:s", $order['add_time']);
// 				$msg .= chr(10) . '付款时间：' . date("Y-m-d H:i:s", $order['pay_time']);
// 				$msg .= chr(10) . '消费时间：' . date("Y-m-d H:i:s", $order['use_time']);
// 				if (empty($order['paid'])) {
// 					$msg .= chr(10) . '订单状态：未支付';
// 				} else {
// 					if (empty($order['status'])) {
// 						$msg .= chr(10) . '订单状态：未消费';
// 					} elseif ($order['status'] == 1) {
// 						$msg .= chr(10) . '订单状态：已消费';
// 					} elseif ($order['status'] == 2) {
// 						$msg .= chr(10) . '订单状态：已完成';
// 					} elseif ($order['status'] == 3) {
// 						$msg .= chr(10) . '订单状态：已退款';
// 					}
// 				}
// 				$pay_type = isset($payarr[$order['pay_type']]) ? $payarr[$order['pay_type']] : '';
// 				$pay_type && $msg .= chr(10) . '支付方式：' . $pay_type;
// 				$msg .= chr(10) . '*******************************';
// 				$msg .= chr(10) . '店铺名称：' . $store['name'];
// 				$msg .= chr(10) . '店铺电话：' . $store['phone'];
// 				$msg .= chr(10) . '店铺地址：' . $store['adress'];
// 				$msg .= chr(10) . '打印时间：' . date("Y-m-d H:i:s");
// 				$msg .= chr(10) . '谢谢惠顾，欢迎再次光临！';
// 				return $msg;

// 			}
			
// 			$print_format = preg_replace('/\{user_name\}/', $data['user_name'], $print_format);
// 			$print_format = preg_replace('/\{user_phone\}/', $data['user_phone'], $print_format);
// 			$print_format = preg_replace('/\{user_address\}/', $data['user_address'], $print_format);
// 			$print_format = preg_replace('/\{user_message\}/', $data['user_message'], $print_format);
// 			$print_format = preg_replace('/\{buy_time\}/', $data['buy_time'], $print_format);
// 			$print_format = preg_replace('/\{pay_time\}/', $data['pay_time'], $print_format);
// 			$print_format = preg_replace('/\{use_time\}/', $data['use_time'], $print_format);
// 			$goods_list = '';
// 			if (isset($data['goods_list'])) {
// 				foreach ($data['goods_list'] as $k => $row) {
// 					if ($k) {
// 						$goods_list .= chr(10). $row['name'] . ": ￥" . $row['price'] . " * " . $row['num'];
// 					} else {
// 						$goods_list .= $row['name'] . ": ￥" . $row['price'] . " * " . $row['num'];
// 					}
// 				}
// 			}
// 			$print_format = preg_replace('/\{goods_list\}/', $goods_list, $print_format);
// 			$print_format = preg_replace('/\{goods_count\}/', $data['goods_count'], $print_format);
// 			$print_format = preg_replace('/\{goods_price\}/', $data['goods_price'], $print_format);
// 			$print_format = preg_replace('/\{minus_price\}/', $data['minus_price'], $print_format);
// 			$print_format = preg_replace('/\{true_price\}/', $data['true_price'], $print_format);
			
// 			$print_format = preg_replace('/\{orderid\}/', $data['orderid'], $print_format);
// 			$print_format = preg_replace('/\{store_name\}/', $data['store_name'], $print_format);
// 			$print_format = preg_replace('/\{store_phone\}/', $data['store_phone'], $print_format);
// 			$print_format = preg_replace('/\{store_address\}/', $data['store_address'], $print_format);
			
// 			$pay_type = isset($payarr[$array['pay_type']]) ? $payarr[$array['pay_type']] : '未选择';
// 			$pay_status = $paid ? '已支付' : '未支付';
// 			$print_format = preg_replace('/\{pay_status\}/', $pay_status, $print_format);
// 			$print_format = preg_replace('/\{pay_type\}/', $pay_type, $print_format);
// 			$print_format = preg_replace('/\{print_time\}/', date('Y-m-d H:i:s'), $print_format);
// 			return $print_format;
			
// 			$msg = '';
// 			if (isset($array['user_name']) && $array['user_name']) $msg .= chr(10).'姓名：'. $array['user_name'];
// 			if (isset($array['user_phone']) && $array['user_phone']) $msg .= chr(10).'电话：'. $array['user_phone'];
// 			if (isset($array['user_address']) && $array['user_address']) $msg .= chr(10).'地址：'. $array['user_address'];
// 			if (isset($array['buy_time']) && $array['buy_time']) $msg .= chr(10).'下单时间：'. date('Y-m-d H:i:s', $array['buy_time']);
// 			if (isset($array['goods_list']) && $array['goods_list']) {
// 				$msg .= chr(10).'*******************************';
// 				foreach ($array['goods_list'] as $row) {
// 					$msg .= chr(10). $row['name'] . ": ￥" . $row['price'] . " * " . $row['num'];
// 				}
// 				$msg .= chr(10).'菜品数:' . $msg['goods_count'];
// 				$msg .= chr(10).'总价: ￥' . $msg['goods_count'];
// 				$msg .= chr(10).'*******************************';
// 			}
			
// 			if ($paid) {
// 				$msg .= chr(10).'订单状态：已付款';
// 			} else {
// 				$msg .= chr(10).'订单状态：未付款';
// 			}
// 			isset($array['pay_type'])&& array_key_exists($array['pay_type'],$payarr)&& $msg.=chr(10)."支付方式：".$payarr[$array['pay_type']];
// 			$msg .= chr(10).'※※※※※※※※※※※※※※※※';
// 			if (isset($array['store_name']) && $array['store_name']) $msg .= chr(10).'公司名称：'.$array['store_name'];
// 			if (isset($array['store_phone']) && $array['store_phone']) $msg .= chr(10).'公司电话：'.$array['store_phone'];
// 			if (isset($array['store_address']) && $array['store_address']) $msg .= chr(10).'公司地址：'.$array['store_address'];
// 			$msg .= chr(10).'打印时间：'.date("Y-m-d H:i:s");
// 			return $msg;
// 		}
// 	}


	public static function array_to_str($order_id, $table = 'meal_order')
	{
		$order = D(ucfirst($table))->field(true)->where(array('order_id' => $order_id))->find();
		if (is_array($order)) {
			$store = D('Merchant_store')->field(true)->where(array('store_id' => $order['store_id']))->find();
			$payarr = array('alipay' => '支付宝', 'weixin' => '微信支付', 'tenpay' => '财付通[wap手机]', 'tenpaycomputer' => '财付通[即时到帐]', 'yeepay' => '易宝支付', 'allinpay' => '通联支付', 'daofu' => '货到付款', 'dianfu' => '到店付款', 'chinabank' => '网银在线', 'offline' => '线下支付');
			if ($table == 'meal_order') {
				$print_format = C('config.print_format');
				$print_format = preg_replace('/\{user_name\}/', $order['name'], $print_format);
				$print_format = preg_replace('/\{user_phone\}/', $order['phone'], $print_format);
				$print_format = preg_replace('/\{user_address\}/', $order['address'], $print_format);
				$print_format = preg_replace('/\{user_message\}/', $order['note'], $print_format);
				$print_format = preg_replace('/\{user_num\}/', $order['num'], $print_format);
				$print_format = preg_replace('/\{buy_time\}/', date("Y-m-d H:i:s", $order['dateline']), $print_format);
				$print_format = preg_replace('/\{pay_time\}/', date("Y-m-d H:i:s", $order['pay_time']), $print_format);
				$print_format = preg_replace('/\{use_time\}/', date("Y-m-d H:i:s", $order['use_time']), $print_format);
				if ($order['arrive_time']) {
					$print_format = preg_replace('/\{arrive_time\}/', date("Y-m-d H:i:s", $order['arrive_time']), $print_format);
				} else {
					$print_format = preg_replace('/\{arrive_time\}/', '尽快送达', $print_format);
				}
				
				$goods_list = '';
				if ($order['info']) {
					$list = unserialize($order['info']);
					foreach ($list as $k => $row) {
						$goods_list .= chr(10) . $row['name'] . ": ￥" . $row['price'] . " * " . $row['num'];
						$row['omark'] && $goods_list .= chr(10) . "菜品备注: " . $row['omark'];
					}
				}
				$print_format = preg_replace('/\{goods_list\}/', $goods_list, $print_format);
				$print_format = preg_replace('/\{goods_count\}/', $order['total'], $print_format);
				$print_format = preg_replace('/\{goods_price\}/', $order['total_price'], $print_format);
				$print_format = preg_replace('/\{minus_price\}/', $order['minus_price'], $print_format);
				$print_format = preg_replace('/\{true_price\}/', $order['total_price'] - $order['minus_price'], $print_format);
				$print_format = preg_replace('/\{orderid\}/', $order['order_id'], $print_format);
				if (empty($order['paid'])) {
					$pay_status = '未支付';
				} else {
					if (empty($order['status'])) {
						$pay_status = '未消费';
					} elseif ($order['status'] == 1) {
						$pay_status = '已消费';
					} elseif ($order['status'] == 2) {
						$pay_status = '已完成';
					} elseif ($order['status'] == 3) {
						$pay_status = '已退款';
					}
				}
				$print_format = preg_replace('/\{pay_status\}/', $pay_status, $print_format);
				$pay_type = isset($payarr[$order['pay_type']]) ? $payarr[$order['pay_type']] : '';
				if (empty($pay_type)) {
					if ($order['price'] == $order['balance_pay']) {
						$pay_type = '平台余额支付';
					} elseif ($order['price'] == $order['merchant_balance']) {
						$pay_type = '商户余额支付';
					}
				}
				$print_format = preg_replace('/\{pay_type\}/', $pay_type, $print_format);
				$table_name = '未选择';
				if ($order['meal_type']) {
					$meal_type = '外送';
				} else {
					$meal_type = '预定';
					if ($table = D('Merchant_store_table')->field(true)->where(array('pigcms_id' => $order['tableid']))->find()) {
						$table_name = $table['name'];
					}
				}
				$print_format = preg_replace('/\{delivery_fee\}/', $order['delivery_fee'], $print_format);
				$print_format = preg_replace('/\{meal_type\}/', $meal_type, $print_format);
				$print_format = preg_replace('/\{table_name\}/', $table_name, $print_format);
				
				$print_format = preg_replace('/\{store_name\}/', $store['name'], $print_format);
				$print_format = preg_replace('/\{store_phone\}/', $store['phone'], $print_format);
				$print_format = preg_replace('/\{store_address\}/', $store['adress'], $print_format);
					
				$print_format = preg_replace('/\{print_time\}/', date('Y-m-d H:i:s'), $print_format);
				return $print_format;
			} else {
				$print_format = C('config.group_print_format');
				$user = D('User')->field(true)->where(array('uid' => $order['uid']))->find();
				$nickname = isset($user['nickname']) ? $user['nickname'] : '';
				
				$print_format = preg_replace('/\{user_name\}/', $nickname, $print_format);
				$print_format = preg_replace('/\{user_phone\}/', $order['phone'], $print_format);
				$print_format = preg_replace('/\{user_address\}/', $order['adress'], $print_format);
				$print_format = preg_replace('/\{orderid\}/', $order['order_id'], $print_format);
				
				$print_format = preg_replace('/\{goods_name\}/', $order['order_name'], $print_format);
				$print_format = preg_replace('/\{goods_count\}/', $order['num'], $print_format);
				$print_format = preg_replace('/\{goods_price\}/', $order['total_money'], $print_format);
				$print_format = preg_replace('/\{minus_price\}/', $order['wx_cheap'], $print_format);
				$print_format = preg_replace('/\{true_price\}/', $order['total_money'] - $order['wx_cheap'], $print_format);
				
				$print_format = preg_replace('/\{buy_time\}/', date("Y-m-d H:i:s", $order['add_time']), $print_format);
				$print_format = preg_replace('/\{pay_time\}/', date("Y-m-d H:i:s", $order['pay_time']), $print_format);
				$print_format = preg_replace('/\{use_time\}/', date("Y-m-d H:i:s", $order['use_time']), $print_format);
		
				$print_format = preg_replace('/\{store_name\}/', $store['name'], $print_format);
				$print_format = preg_replace('/\{store_phone\}/', $store['phone'], $print_format);
				$print_format = preg_replace('/\{store_address\}/', $store['adress'], $print_format);
				
				if (empty($order['paid'])) {
					$pay_status = '未支付';
				} else {
					if (empty($order['status'])) {
						$pay_status = '未消费';
					} elseif ($order['status'] == 1) {
						$pay_status = '已消费';
					} elseif ($order['status'] == 2) {
						$pay_status = '已完成';
					} elseif ($order['status'] == 3) {
						$pay_status = '已退款';
					}
				}
				
				$pay_type = isset($payarr[$order['pay_type']]) ? $payarr[$order['pay_type']] : '';
				$print_format = preg_replace('/\{pay_status\}/', $pay_status, $print_format);
				$print_format = preg_replace('/\{pay_type\}/', $pay_type, $print_format);
				$print_format = preg_replace('/\{print_time\}/', date('Y-m-d H:i:s'), $print_format);
				return $print_format;
			}
		}
	}
}
