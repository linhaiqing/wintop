<?php
class Appoint_orderModel extends Model{
	/*获取订单详情*/
	public function get_order_detail_by_id($uid, $order_id, $is_wap=false, $check_user=true){
		$database_appoint = D('Appoint');
		$database_appoint_order = D('Appoint_order');
		$database_user = D('User');
		if($check_user){
			$condition_appoint['uid'] = $uid;
		} else {
			$condition_appoint['uid'] = '';
		}
		$condition_appoint['order_id'] = $order_id;
		
		$now_order = $database_appoint_order->field(true)->where($condition_appoint)->find();
		$where['appoint_id'] = $now_order['appoint_id'];
		$appoint_info = $database_appoint->field('`pic`, `appoint_name`, `appoint_price`, `appoint_type`, `start_time`, `end_time`, `payment_status`')->where($where)->find();
		$tmp_pic_arr = explode(';', $appoint_info['pic']);
		$appoint_image_class = new appoint_image();
		$now_order['list_pic'] = $appoint_image_class->get_image_by_path($tmp_pic_arr[0],'s');
		$now_order['appoint_name'] = $appoint_info['appoint_name'];
		$now_order['appoint_price'] = $appoint_info['appoint_price'];
		$now_order['appoint_type'] = $appoint_info['appoint_type'];
		$now_order['start_time'] = $appoint_info['start_time'];
		$now_order['end_time'] = $appoint_info['end_time'];
		$now_order['payment_status'] = $appoint_info['payment_status'];
		$now_order['pay_type_txt'] = D('Pay')->get_pay_name($now_order['pay_type'], $now_order['is_mobile_pay']);
		$now_order['url'] = $database_appoint->get_appoint_url($now_order['appoint_id'], $is_wap);
		$condition_user['uid'] = $uid;
		$user_info = $database_user->field('`phone`')->where($condition_user)->find();
		$now_order['phone'] = $user_info['phone'];
		
		return $now_order;
	}
	
	// 下单
	public function save_post_form($appoint,$uid,$order_id){
		
		$data_appoint_order['uid'] = $uid;
		$data_appoint_order['appoint_id'] = $appoint['appoint_id'];
		$data_appoint_order['mer_id'] = $appoint['mer_id'];
		$data_appoint_order['order_time'] = $_SERVER['REQUEST_TIME'];
		$data_appoint_order['payment_money'] = $appoint['payment_money'];
		$data_appoint_order['content'] = $appoint['content'];
		$data_appoint_order['appoint_type'] = $appoint['appoint_type'];
		$data_appoint_order['product_id'] = $appoint['product_id'];
		$data_appoint_order['cue_field'] = $appoint['cue_field'];
		$data_appoint_order['appoint_time'] = $appoint['appoint_time'];
		$data_appoint_order['appoint_date'] = $appoint['appoint_date'];
		$data_appoint_order['store_id'] = $appoint['store_id'];
		
		$now_user = D('User')->get_user($uid);
		if(empty($now_user)){
			return array('error'=>1,'msg'=>'未获取到您的帐号信息，请重试！');
		}
		if($order_id){
			$condition_group_order['order_id'] = $order_id;
			$condition_group_order['uid'] = $uid;
			$save_result = $this->where($condition_group_order)->data($data_appoint_order)->save();
			if($save_result){
				return array('error'=>0,'msg'=>'订单修改成功！','order_id'=>$order_id);
			}else{
				return array('error'=>1,'msg'=>'订单修改失败！请重试','order_id'=>$order_id);
			}
		}else{
			$data_appoint_order['uid'] = $uid;
			
			$order_id = $this->data($data_appoint_order)->add();
			
			if($order_id){
				if ($_SESSION['openid']) {
					$href = C('config.site_url').'/wap.php?c=My&a=appoint_order&order_id='.$order_id;
					$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
					$model->sendTempMsg('OPENTM201682460', array('href' => $href, 'wecha_id' => $_SESSION['openid'], 'first' => '您好，您的订单已生成', 'keyword3' => $order_id, 'keyword1' => date('Y-m-d H:i:s'), 'keyword2' => $appoint['appoint_name'], 'remark' => '您的该次预约下单成功，感谢您的使用！'));
				}
				$sms_data = array('mer_id' => $appoint['mer_id'], 'store_id' => 0, 'type' => 'appoint');
				if (C('config.sms_place_order') == 1 || C('config.sms_place_order') == 3) {
					$sms_data['uid'] = $uid;
					$sms_data['mobile'] = $now_user['phone'];
					$sms_data['sendto'] = 'user';
					$sms_data['content'] = '您在' . date("Y-m-d H:i:s") . '时，成功预约了' . $appoint['appoint_name'] . '，已成功生产订单，订单号：' . $order_id;
					Sms::sendSms($sms_data);
				}
				if (C('config.sms_place_order') == 2 || C('config.sms_place_order') == 3) {
					$merchant = D('Merchant')->where(array('mer_id' => $appoint['mer_id']))->find();
					$sms_data['uid'] = 0;
					$sms_data['mobile'] = $merchant['phone'];
					$sms_data['sendto'] = 'merchant';
					$sms_data['content'] = '有份新的' . $appoint['appoint_name'] . '被预约，订单号：' . $order_id . '请您注意查看并处理!';
					Sms::sendSms($sms_data);
				}
				
				return array('error'=>0,'msg'=>'订单产生成功！','order_id'=>$order_id);
			}else{
				return array('error'=>1,'msg'=>'订单产生失败！请重试');
			}
		}
	}
	
	public function get_order_by_id($uid,$order_id){
		$condition_group_order['order_id'] = $order_id;
		$condition_group_order['uid'] = $uid;
		$order = $this->field(true)->where($condition_group_order)->find();
		return $order;
	}
	public function get_pay_order($uid,$order_id,$is_web=false){
		$now_order = $this->get_order_by_id($uid,$order_id);
		if(empty($now_order)){
			return array('error'=>1,'msg'=>'当前订单不存在！');
		}
		if(!empty($now_order['paid'])){
			if($is_web){
				return array('error'=>1,'msg'=>'您已经支付过此订单！','url'=>U('User/Index/appoint_order_view',array('order_id'=>$now_order['order_id'])));
			}else{
				return array('error'=>1,'msg'=>'您已经支付过此订单！','url'=>U('My/appoint_order',array('order_id'=>$now_order['order_id'])));
			}
		}
		
		$now_group = D('Appoint')->get_appoint_by_appointId($now_order['appoint_id']);
		if(empty($now_group)){
			return array('error'=>1,'msg'=>'当前预约不存在或已过期！');
		}
		
		if($is_web){
			$order_info = array(
					'order_id'			=>	$now_order['order_id'],
					'mer_id'			=>	$now_order['mer_id'],
					'order_type'		=>	'appoint',
					'order_total_money'	=>	floatval($now_order['payment_money']),
					'order_content'    =>  array(
							0=>array(
									'name'  		=> $now_group['merchant_name'].'：'.$now_group['appoint_name'],
									'num'   		=> 1,
									'price' 		=> floatval($now_order['payment_money']),
									'money' 	=> floatval($now_order['payment_money']),
							)
					)
			);
		}else{
			$order_info = array(
					'order_id'			=>	$now_order['order_id'],
					'appoint_id'			=>	$now_order['appoint_id'],
					'mer_id'			=>	$now_order['mer_id'],
					'order_type'		=>	'appoint',
					'order_name'		=>	$now_group['appoint_name'],
					'order_num'			=>	$now_order['num'],
					'order_price'		=>	floatval($now_order['payment_money']),
					'order_total_money'	=>	floatval($now_order['payment_money']),
			);
		}
		
		return array('error'=>0,'order_info'=>$order_info);
	}
	
	// 已经约满的时间点
	public function get_appoint_num($appoint_id,$sum){
		$time = date('Y-m-d');
		$sql = "SELECT count(*) as appointNum,appoint_date,appoint_time from pigcms_appoint_order 
				where appoint_date >='".$time."' and service_status=0 AND appoint_id = ".$appoint_id."
				group by appoint_date,appoint_time
				having appointNum=".$sum;
		$result = D('')->query($sql);
		return $result;
	}
	
	/*获得订单链接*/
	public function get_order_url($order_id,$is_wap=false){
		if($is_wap){
			return U('My/appoint_order',array('order_id'=>$order_id));
		}else{
			return U('User/Index/appoint_order_view',array('order_id'=>$order_id));
		}
	}
	
	//电脑站支付前订单处理
	public function web_befor_pay($order_info,$now_user){
		//判断是否需要在线支付
		if($now_user['now_money'] < $order_info['order_total_money']){
			$online_pay = true;
		}else{
			$online_pay = false;
		}
		//不使用在线支付，直接使用用户余额。
		if(empty($online_pay)){
			// $money_pay_result = D('User')->user_money($now_user['uid'],$order_info['order_total_money'],'预约 '.$order_info['order_name'].'*'.$order_info['order_num']);
			// if($money_pay_result['error_code']){
				// return $money_pay_result;
			// }
			$order_pay['balance_pay'] = $order_info['order_total_money'];
		}else{
			if(!empty($now_user['now_money'])){
				$order_pay['balance_pay'] = $now_user['now_money'];
			}
		}
		
		//将已支付用户余额等信息记录到订单信息里
		if(!empty($order_pay['balance_pay'])){
			$data_group_order['balance_pay'] = $order_pay['balance_pay'];	
		}
		if(!empty($data_group_order)){
//			$data_group_order['wx_cheap'] 			= 0;
			$data_group_order['card_id'] 			= 0;
			$data_group_order['merchant_balance'] 	= 0;
			$condition_group_order['order_id'] = $order_info['order_id'];
			if(!$this->where($condition_group_order)->data($data_group_order)->save()){
				return array('error_code'=>true,'msg'=>'保存订单失败！请重试或联系管理员。');
			}
		}
		
		if($online_pay){
			return array('error_code'=>false,'pay_money'=>$order_info['order_total_money'] - $now_user['now_money']);
		}else{
			$order_param = array(
				'order_id' => $order_info['order_id'],
				'pay_type' => '',
				'third_id' => '',
				'is_mobile' => 0,
			);
			$result_after_pay = $this->after_pay($order_param);
			if($result_after_pay['error']){
				return array('error_code'=>true,'msg'=>$result_after_pay['msg']);
			}
			return array('error_code'=>false,'msg'=>'支付成功！','url'=>U('User/Index/appoint_order_view',array('order_id'=>$order_info['order_id'])));
		}
	}
	//手机端支付前订单处理
	public function wap_befor_pay($order_info,$now_coupon,$merchant_balance,$now_user){

		//去除微信优惠的金额
//		$pay_money = $order_info['order_total_money'] - $wx_cheap;
//		$data_group_order['wx_cheap'] = $wx_cheap;
		$pay_money = $order_info['order_total_money'];
		$data_group_order['pay_money'] = $pay_money;
		//判断优惠券
		if(!empty($now_coupon['price'])){
			$data_group_order['card_id'] = $now_coupon['record_id'];
			if($now_coupon['price'] >= $pay_money){
				$order_result = $this->wap_pay_save_order($order_info,$data_group_order);
				if($order_result['error_code']){
					return $order_result;
				}
				return $this->wap_after_pay_before($order_info);
			}
			$pay_money -= $now_coupon['price'];
		}
		
		//判断商家余额
		if(!empty($merchant_balance)){
			if($merchant_balance >= $pay_money){
				$data_group_order['merchant_balance'] = $pay_money;
				$order_result = $this->wap_pay_save_order($order_info,$data_group_order);
				if($order_result['error_code']){
					return $order_result;
				}
				return $this->wap_after_pay_before($order_info);
			}else{
				$data_group_order['merchant_balance'] = $merchant_balance;
			}
			$pay_money -= $merchant_balance;
		}
		
		//判断帐户余额
		if(!empty($now_user['now_money'])){
			if($now_user['now_money'] >= $pay_money){
				$data_group_order['balance_pay'] = $pay_money;
				$order_result = $this->wap_pay_save_order($order_info,$data_group_order);
				if($order_result['error_code']){
					return $order_result;
				}
				return $this->wap_after_pay_before($order_info);
			}else{
				$data_group_order['balance_pay'] = $now_user['now_money'];
			}
			$pay_money -= $now_user['now_money'];
		}
		//在线支付
		$order_result = $this->wap_pay_save_order($order_info,$data_group_order);
		if($order_result['error_code']){
			return $order_result;
		}
		return array('error_code'=>false,'pay_money'=>$pay_money);
	}
	
	//手机端支付前保存各种支付信息
	public function wap_pay_save_order($order_info,$data_group_order){
//		$data_group_order['wx_cheap'] 			= !empty($data_group_order['wx_cheap']) ? $data_group_order['wx_cheap'] : 0;
		$data_group_order['card_id'] 			= !empty($data_group_order['card_id']) ? $data_group_order['card_id'] : 0;
		$data_group_order['merchant_balance'] 	= !empty($data_group_order['merchant_balance']) ? $data_group_order['merchant_balance'] : 0;
		$data_group_order['balance_pay']	 	= !empty($data_group_order['balance_pay']) ? $data_group_order['balance_pay'] : 0;
		$data_group_order['pay_money']	 	    = !empty($data_group_order['pay_money']) ? $data_group_order['pay_money'] : 0;
		$data_group_order['submit_order_time'] = $_SERVER['REQUEST_TIME'];
		$condition_group_order['order_id'] = $order_info['order_id'];
		if($this->where($condition_group_order)->data($data_group_order)->save()){
			return array('error_code'=>false,'msg'=>'保存订单成功！');
		}else{
			return array('error_code'=>true,'msg'=>'保存订单失败！请重试或联系管理员。');
		}
	}
	//如果无需调用在线支付，使用此方法即可。
	public function wap_after_pay_before($order_info){
		$order_param = array(
				'order_id' => $order_info['order_id'],
				'pay_type' => '',
				'third_id' => '',
				'is_mobile' => 0,
			);
			$result_after_pay = $this->after_pay($order_param);
			if($result_after_pay['error']){
				return array('error_code'=>true,'msg'=>$result_after_pay['msg']);
			}
			return array('error_code'=>false,'msg'=>'支付成功！','url'=>str_replace('/source/','/',U('My/appoint_order',array('order_id'=>$order_info['order_id']))));
	}
	//支付前订单处理
	public function befor_pay($order_info,$now_coupon,$now_user){
		//判断是否需要在线支付
		if($now_coupon['price']+$now_user['now_money'] < $order_info['order_total_money']){
			$online_pay = true;
		}else{
			$online_pay = false;
		}
		//不使用在线支付，直接使用会员卡和用户余额。
		if(empty($online_pay)){
			if(!empty($now_coupon)){
				$coupon_pay_result = D('Member_card_coupon')->user_card($now_coupon['record_id'],$order_info['mer_id'],$now_user['uid']);
				if($coupon_pay_result['error_code']){
					return $coupon_pay_result;
				}
				$order_pay['car_id'] = $now_coupon['record_id'];
			}
			if(!empty($now_user['now_money']) && $now_coupon['price'] < $order_info['order_total_money']){
				$money_pay_result = D('User')->user_money($now_user['uid'],$order_info['order_total_money']-$now_coupon['price']);
				if($money_pay_result['error_code']){
					return $money_pay_result;
				}
				$order_pay['balance_pay'] = $now_user['now_money'];
			}
		}else{
			//校验会员卡
			if(!empty($now_coupon)){
				$coupon_pay_result = D('Member_card_coupon')->check_card($now_coupon['record_id'],$order_info['mer_id'],$now_user['uid']);
				if($coupon_pay_result['error_code']){
					return $coupon_pay_result;
				}
				$order_pay['car_id'] = $now_coupon['record_id'];
			}
			if(!empty($now_user['now_money'])){
				$order_pay['balance_pay'] = $now_user['now_money'];
			}
		}
		
		//将会员卡ID，已支付用户余额等信息记录到订单信息里
		if(!empty($order_pay['car_id'])){
			$data_group_order['card_id'] = $order_pay['record_id'];
		}
		if(!empty($order_pay['balance_pay'])){
			$data_group_order['balance_pay'] = $order_pay['record_id'];	
		}
		if(!empty($data_group_order)){
			$condition_group_order['order_id'] = $now_order['order_id'];
			if(!$this->where($condition_group_order)->data($data_group_order)->save()){
				return array('error_code'=>true,'msg'=>'保存订单失败！请重试或联系管理员。');
			}
		}
		
		if($online_pay){
			return array('error_code'=>false,'pay_money'=>$order_info['order_total_money'] - $now_coupon['price'] - $now_user['now_money']);
		}else{
			$order_param = array(
				'order_id' => $order_info['order_id'],
				'pay_type' => '',
				'third_id' => '',
				'is_mobile' => 0,
				'pay_money' => 0,
			);
			$result_after_pay = $this->after_pay($order_param);
			if($result_after_pay['error']){
				return array('error_code'=>true,'msg'=>$result_after_pay['msg']);
			}
			return array('error_code'=>false,'url'=>U('My/appoint_order',array('order_id'=>$order_info['order_id'])));
		}
	}
	
	//支付之后
	public function after_pay($order_param){
		$condition_group_order['order_id'] = $order_param['order_id'];
		$now_order = $this->field(true)->where($condition_group_order)->find();
		if(empty($now_order)){
			return array('error'=>1,'msg'=>'当前订单不存在！');
		}else if($now_order['paid'] == 1){
			if($order_param['is_mobile']){
				return array('error'=>1,'msg'=>'该订单已付款！','url'=>U('My/appoint_order',array('order_id'=>$now_order['order_id'])));
			}else{
				return array('error'=>1,'msg'=>'该订单已付款！','url'=>U('User/Index/appoint_order_view',array('order_id'=>$now_order['order_id'])));
			}
		}else{
			//得到当前用户信息，不将session作为调用值，因为可能会失效或错误。
			$now_user = D('User')->get_user($now_order['uid']);
			if(empty($now_user)){
				return array('error'=>1,'msg'=>'没有查找到此订单归属的用户，请联系管理员！');
			}
			
			//判断优惠券是否正确
			if($now_order['card_id']){
				$now_coupon = D('Member_card_coupon')->get_coupon_by_recordid($now_order['card_id'],$now_order['uid']);
				if(empty($now_coupon)){
					return $this->wap_after_pay_error($now_order,$order_param,'您选择的优惠券不存在！');
				}
			}
			
			//判断会员卡余额
			$merchant_balance = floatval($now_order['merchant_balance']);
			if($merchant_balance){
				$user_merchant_balance = D('Member_card')->get_balance($now_order['uid'],$now_order['mer_id']);
				if($user_merchant_balance < $merchant_balance){
					return $this->wap_after_pay_error($now_order,$order_param,'您的会员卡余额不够此次支付！');
				}
			}
			//判断帐户余额
			$balance_pay = floatval($now_order['balance_pay']);
			if($balance_pay){
				if($now_user['now_money'] < $balance_pay){
					return $this->wap_after_pay_error($now_order,$order_param,'您的帐户余额不够此次支付！');
				}
			}
			
			//如果使用了优惠券
			if($now_order['card_id']){
				$use_result = D('Member_card_coupon')->user_card($now_order['card_id'],$now_order['mer_id'],$now_order['uid']);
				if($use_result['error_code']){
					return array('error'=>1,'msg'=>$use_result['msg']);
				}
			}
			
			//如果使用会员卡余额
			if($merchant_balance){
				$use_result = D('Member_card')->use_card($now_order['uid'],$now_order['mer_id'],$merchant_balance,'预约 '.$now_order['order_name'].' 扣除会员卡余额');
				if($use_result['error_code']){
					return array('error'=>1,'msg'=>$use_result['msg']);
				}
			}
			//如果用户使用了余额支付，则扣除相应的金额。
			if(!empty($balance_pay)){
				$use_result = D('User')->user_money($now_order['uid'],$balance_pay,'预约 '.$now_order['order_name'].' 扣除余额');
				if($use_result['error_code']){
					return array('error'=>1,'msg'=>$use_result['msg']);
				}
			}
			
			$condition_group_order['order_id'] = $order_param['order_id'];
			 
			$group_pass_array = array(
					date('y',$_SERVER['REQUEST_TIME']),
					date('m',$_SERVER['REQUEST_TIME']),
					date('d',$_SERVER['REQUEST_TIME']),
					date('H',$_SERVER['REQUEST_TIME']),
					date('i',$_SERVER['REQUEST_TIME']),
					date('s',$_SERVER['REQUEST_TIME']),
					mt_rand(10,99),
			);
			shuffle($group_pass_array);
			$data_group_order['appoint_pass'] = implode('',$group_pass_array);
			
			$data_group_order['pay_time'] = $_SERVER['REQUEST_TIME'];
			$data_group_order['pay_money'] = floatval($order_param['pay_money']);
			$data_group_order['pay_type'] = $order_param['pay_type'];
			$data_group_order['third_id'] = $order_param['third_id'];
			$data_group_order['is_mobile_pay'] = $order_param['is_mobile'];
			$data_group_order['is_own'] = $order_param['is_own'];
			$data_group_order['paid'] = 1;
			
			if($this->where($condition_group_order)->data($data_group_order)->save()){
				
				/*分析粉丝行为*/
				D('Merchant_request')->add_request($now_order['mer_id'],array('appoint_buy_count'=>1,'appoint_buy_money'=>$now_order['payment_money']));
			 
				$condition_group['appoint_id'] = $now_order['appoint_id'];
				D('Appoint')->where($condition_group)->setInc('appoint_sum',1);
				 
				if ($now_user['openid'] && $order_param['is_mobile']) {
					$href = C('config.site_url').'/wap.php?c=My&a=appoint_order&order_id='.$now_order['order_id'];
					$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
					$model->sendTempMsg('OPENTM201752540', array('href' => $href, 'wecha_id' => $now_user['openid'], 'first' => '预约提醒', 'keyword1' => $now_order['order_name'], 'keyword2' => $now_order['order_id'], 'keyword3' => $now_order['total_money'], 'keyword4' => date('Y-m-d H:i:s'), 'remark' => '预约成功，您的消费码：'.$data_group_order['appoint_pass']));
				}
				$sms_data = array('mer_id' => $now_order['mer_id'], 'store_id' => 0, 'type' => 'appoint');
				if (C('config.sms_success_order') == 1 || C('config.sms_success_order') == 3) {
					$sms_data['uid'] = $now_order['uid'];
					$sms_data['mobile'] = $now_order['phone'];
					$sms_data['sendto'] = 'user';
					$sms_data['content'] = '您预约 '.$now_order['order_name'].'的订单(订单号：' . $now_order['order_id'] . ')已经完成支付,您的消费码：' . $data_group_order['appoint_pass'];
					Sms::sendSms($sms_data);
				}
				if (C('config.sms_success_order') == 2 || C('config.sms_success_order') == 3) {
					$merchant = D('Merchant')->where(array('mer_id' => $now_order['mer_id']))->find();
					$sms_data['uid'] = 0;
					$sms_data['mobile'] = $merchant['phone'];
					$sms_data['sendto'] = 'merchant';
					$sms_data['content'] = '顾客预约的' . $now_order['order_name'] . '的订单(订单号：' . $now_order['order_id'] . '),在' . date('Y-m-d H:i:s') . '时已经完成了支付！';
					Sms::sendSms($sms_data);
				}
				
				if($order_param['is_mobile']){
					return array('error'=>0,'url'=>str_replace('/source/','/',U('My/appoint_order',array('order_id'=>$now_order['order_id']))));
				}else{
					return array('error'=>0,'url'=>U('User/Index/appoint_order_view',array('order_id'=>$now_order['order_id'])));
				}
			}else{
				return array('error'=>1,'msg'=>'修改订单状态失败，请联系系统管理员！');
			}
		}
	}
	//支付时，金额不够，记录到帐号
	public function wap_after_pay_error($now_order,$order_param,$error_tips){
		//记录充值的金额，因为 Pay/return_url 处没有返回order的具体信息，故在此调用。
		$user_result = D('User')->add_money($now_order['uid'],$order_param['pay_money'],'在线充值');
		if($user_result['error_code']){
			return array('error'=>1,'msg'=>$user_result['msg']);
		}else{
			if($order_param['is_mobile']){
				$return_url = str_replace('/source/','/',U('My/appoint_order',array('order_id'=>$now_order['order_id'])));
			}else{
				$return_url = U('User/Index/appoint_order_view',array('order_id'=>$now_order['order_id']));
			}
			return array('error'=>1,'msg'=>$error_tips.'已将您充值的金额添加到您的余额内。','url'=>$return_url);
		}
	}
	
	// 不需要付款
	public function no_pay_after($order_id,$appoint_info,$is_mobile=1){
		
		$condition_group_order['order_id'] = $order_id;
		$now_order = $this->field(true)->where($condition_group_order)->find();
		if(empty($now_order)){
			return array('error'=>1,'msg'=>'当前订单不存在！');
		}else{
			//得到当前用户信息，不将session作为调用值，因为可能会失效或错误。
			$now_user = D('User')->get_user($now_order['uid']);
			if(empty($now_user)){
				return array('error'=>1,'msg'=>'没有查找到此订单归属的用户，请联系管理员！');
			}
			 
			$condition_group_order['order_id'] = $order_id;
			 
			$group_pass_array = array(
					date('y',$_SERVER['REQUEST_TIME']),
					date('m',$_SERVER['REQUEST_TIME']),
					date('d',$_SERVER['REQUEST_TIME']),
					date('H',$_SERVER['REQUEST_TIME']),
					date('i',$_SERVER['REQUEST_TIME']),
					date('s',$_SERVER['REQUEST_TIME']),
					mt_rand(10,99),
			);
			shuffle($group_pass_array);
			$data_group_order['appoint_pass'] = implode('',$group_pass_array);
			if($this->where($condition_group_order)->data($data_group_order)->save()){
				
				/*分析粉丝行为*/
				D('Merchant_request')->add_request($now_order['mer_id'],array('appoint_buy_count'=>1));
				
				$condition_group['appoint_id'] = $now_order['appoint_id'];
				D('Appoint')->where($condition_group)->setInc('appoint_sum',1);
				 
				if ($now_user['openid'] && $is_mobile) {
					$href = C('config.site_url').'/wap.php?c=My&a=appoint_order&order_id='.$now_order['order_id'];
					$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
					$model->sendTempMsg('OPENTM201682460', array('href' => $href, 'wecha_id' => $now_user['openid'], 'first' => '预约提醒', 'keyword1' => $appoint_info['appoint_name'], 'keyword2' => $now_order['order_id'], 'keyword3' => date('Y-m-d H:i:s'), 'remark' => '预约成功，您的消费码：'.$data_group_order['appoint_pass']));
				}
				$sms_data = array('mer_id' => $now_order['mer_id'], 'store_id' => 0, 'type' => 'appoint');
				if (C('config.sms_success_order') == 1 || C('config.sms_success_order') == 3) {
					$sms_data['uid'] = $now_order['uid'];
					$sms_data['mobile'] = $now_order['phone'];
					$sms_data['sendto'] = 'user';
					$sms_data['content'] = '您预约 '.$appoint_info['appoint_name'].'的订单(订单号：' . $now_order['order_id'] . ')已经完成支付,您的消费码：' . $data_group_order['appoint_pass'];
					Sms::sendSms($sms_data);
				}
				if (C('config.sms_success_order') == 2 || C('config.sms_success_order') == 3) {
					$merchant = D('Merchant')->where(array('mer_id' => $now_order['mer_id']))->find();
					$sms_data['uid'] = 0;
					$sms_data['mobile'] = $merchant['phone'];
					$sms_data['sendto'] = 'merchant';
					$sms_data['content'] = '顾客预约的' . $now_order['order_name'] . '的订单(订单号：' . $now_order['order_id'] . '),在' . date('Y-m-d H:i:s') . '时已经完成！';
					Sms::sendSms($sms_data);
				}
				
				if($is_mobile){
					return array('error'=>0,'url'=>str_replace('/source/','/',U('My/appoint_order',array('order_id'=>$now_order['order_id']))));
				}else{
					return array('error'=>0,'url'=>U('User/Index/appoint_order_view',array('order_id'=>$now_order['order_id'])));
				}
			}else{
				return array('error'=>1,'msg'=>'修改订单状态失败，请联系系统管理员！');
			}
		}
	}
	
}