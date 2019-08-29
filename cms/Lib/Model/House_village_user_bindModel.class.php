<?php
class House_village_user_bindModel extends Model{
	/*通过手机号自动绑定业主*/
	public function bind($uid,$phone){
		$this->data(array('uid'=>$uid))->where(array('phone'=>$phone))->save();
	}
	public function get_user_bind_list($uid,$village_id){
		$bind_list = $this->field(true)->where(array('uid'=>$uid,'village_id'=>$village_id))->order('`pigcms_id` DESC')->select();
		return $bind_list;
	}
	/*得到小区下所有的业主列表*/
	public function get_limit_list_page($village_id,$pageSize=20){
		if(!$village_id){
			return null;
		}
		
		$return = array();
		$condition_where['village_id'] = $village_id;
		$count_user = D('House_village_user_bind')->where($condition_where)->count();
		
		import('@.ORG.merchant_page');
		$p = new Page($count_user,$pageSize,'page');
		$user_list = D('House_village_user_bind')->field(true)->where($condition_where)->order('`pigcms_id` DESC')->limit($p->firstRow.','.$p->listRows)->select();
		
		if($user_list){
			$return['totalPage'] = ceil($count_user/$pageSize);
			$return['user_count'] = count($user_list);
			$return['pagebar'] = $p->show();
			$return['user_list'] = $user_list;
		}
		
		return $return;
	}
	/*得到单个业主信息*/
	public function get_one($village_id,$value,$field='uid',$bind_uid=0){
		$condition_user['village_id'] = $village_id;
		$condition_user[$field] = $value;
		$now_user = $this->field(true)->where($condition_user)->find();
		// dump($this);
		if(!empty($now_user)){
			$now_user['water_price'] = floatval($now_user['water_price']);
			$now_user['electric_price'] = floatval($now_user['electric_price']);
			$now_user['gas_price'] = floatval($now_user['gas_price']);
			$now_user['park_price'] = floatval($now_user['park_price']);
			$now_user['property_price'] = floatval($now_user['property_price']);
			if($bind_uid){
				$this->where(array('pigcms_id'=>$now_user['pigcms_id']))->data(array('uid'=>$bind_uid))->save();
			}
		}
		return $now_user;
	}
	/*得到单个业主信息*/
	public function get_one_by_bindId($hq_id){
		$condition_user['pigcms_id'] = $hq_id;
		$now_user = $this->field(true)->where($condition_user)->find();
		if(!empty($now_user)){
			$now_user['water_price'] = floatval($now_user['water_price']);
			$now_user['electric_price'] = floatval($now_user['electric_price']);
			$now_user['gas_price'] = floatval($now_user['gas_price']);
			$now_user['park_price'] = floatval($now_user['park_price']);
			$now_user['property_price'] = floatval($now_user['property_price']);
			if($bind_uid){
				$this->where(array('pigcms_id'=>$now_user['pigcms_id']))->data(array('uid'=>$bind_uid))->save();
			}
		}
		return $now_user;
	}
	/*得到小区下所有的业主列表(绑定微信的)*/
	public function get_limit_list_open($village_id,$pageSize=5){
		if(!$village_id){
			return null;
		}
	
		$return = array();
		
		$condition_table  = array(C('DB_PREFIX').'House_village_user_bind'=>'b',C('DB_PREFIX').'user'=>'u');
		$condition_where = " b.uid = u.uid 	AND  u.openid !='' AND b.uid>0 AND b.village_id=".$village_id;
		$condition_field = ' distinct(u.openid), b.uid,u.openid ';
		// if($bigId !== 0){
			// $condition_where .= " AND b.pigcms_id<=".$bigId." AND b.pigcms_id>=".$smallId;
		// }
		$count_user = D('')->table($condition_table)->where($condition_where)->count('distinct(u.openid)');

		import('@.ORG.merchant_page');
		$p = new Page($count_user,$pageSize,'page');
		$user_list = D('')->table($condition_table)->field($condition_field)->where($condition_where)->order('`b`.`pigcms_id` DESC')->limit($p->firstRow.','.$p->listRows)->select();
	
		if($user_list){
			$return['totalPage'] = ceil($count_user/$pageSize);
			$return['user_count'] = count($user_list);
			$return['pagebar'] = $p->show();
			$return['user_list'] = $user_list;
		}
	
		return $return;
	}
	
	/*得到小区下所有欠费业主列表(绑定微信的)*/
	public function get_pay_list_open($village_id,$pageSize=20,$bigId=0,$smallId=0){
		if(!$village_id){
			return null;
		}
	
		$return = array();
	
		$condition_table  = array(C('DB_PREFIX').'house_village_user_paylist'=>'b',C('DB_PREFIX').'user'=>'u');
		$condition_where = " b.uid = u.uid 	AND  u.openid !='' AND b.uid>0 AND b.village_id=".$village_id;
		$condition_field = ' distinct(u.openid), b.uid,u.openid,b.address ';
		if($bigId !== 0){
			$condition_where .= " AND b.pigcms_id<=".$bigId." AND b.pigcms_id>=".$smallId;
		}
		$count_user = D('')->table($condition_table)->where($condition_where)->count('distinct(u.openid)');

		import('@.ORG.merchant_page');
		$p = new Page($count_user,$pageSize,'page');
		$user_list = D('')->table($condition_table)->field($condition_field)->where($condition_where)->order('`b`.`pigcms_id` DESC')->limit($p->firstRow.','.$p->listRows)->select();
	
		if($user_list){
			$return['totalPage'] = ceil($count_user/$pageSize);
			$return['user_count'] = count($user_list);
			$return['pagebar'] = $p->show();
			$return['user_list'] = $user_list;
		}
	
		return $return;
	}
	
}

?>