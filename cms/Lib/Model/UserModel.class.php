<?php
class UserModel extends Model
{
	/*得到所有用户*/
	public function get_user($uid,$field='uid')
	{
		$condition_user[$field] = $uid;
		$now_user = $this->field(true)->where($condition_user)->find();
		if(!empty($now_user)){
			$now_user['now_money'] = floatval($now_user['now_money']);
		}
		return $now_user;
	}
	/*帐号密码检入*/
	public function checkin($phone,$pwd){
		if (empty($phone)){
			return array('error_code' => true, 'msg' => '手机号不能为空');
		}
		if (empty($pwd)){
			return array('error_code' => true, 'msg' => '密码不能为空');
		}
		$now_user = $this->field(true)->where(array('phone' => $phone))->find();
		if ($now_user){
			if($now_user['pwd'] != md5($pwd)){
				return array('error_code' => true, 'msg' => '密码不正确!');
			}
			if(empty($now_user['status'])){
				return array('error_code' => true, 'msg' => '该帐号被禁止登录!');
			}
			$condition_save_user['uid'] = $now_user['uid'];
			$data_save_user['last_time'] = $_SERVER['REQUEST_TIME'];
			$data_save_user['last_ip'] = get_client_ip(1);
						/****判断此用户是否在user_import表中***/
			$user_importDb=D('User_import');
			$user_import=$user_importDb->where(array('telphone'=>$phone,'isuse'=>'0'))->find();
			if(!empty($user_import)){
			   !empty($user_import['ppname']) && $data_save_user['truename']=$user_import['ppname'];
			   $data_save_user['qq']=$user_import['qq'];
			   $data_save_user['email']=$user_import['email'];
			   $data_save_user['level']=max($now_user['level'],$user_import['level']);
			   $data_save_user['score_count']=max($now_user['score_count'],$user_import['integral']);
			   $data_save_user['now_money']=max($now_user['now_money'],$user_import['money']);
			   $data_save_user['importid']=$user_import['id'];
			   //$data_save_user['status']=2; /***未审核****/
			   $mer_id=$user_import['mer_id'];
			   $data_save_user['openid']=isset($_SESSION['weixin']) && isset($_SESSION['weixin']['user']) ? $_SESSION['weixin']['user']['openid'] :'';
			   if(($mer_id>0) && !empty($data_save_user['openid'])){
			      $merchant_user_relationDb=M('Merchant_user_relation');
				  $mwhere=array('openid'=>$data_save_user['openid'],'mer_id'=>$mer_id);
				  $mtmp=$merchant_user_relationDb->where($mwhere)->find();
				  if(empty($mtmp)){
					 $mwhere['dateline']=time();
					 $mwhere['from_merchant']=3;
				     $merchant_user_relationDb->add($mwhere);
				  }
			   }
			}

			if($this->where($condition_save_user)->data($data_save_user)->save()){
			    if(!empty($user_import)){
				   $user_importDb->where(array('id'=>$user_import['id']))->save(array('isuse'=>1));
				}
			}
			return array('error_code' => false, 'msg' => 'OK' ,'user'=>$now_user);
		} else {
			return array('error_code' => true, 'msg' => '手机号不存在!');
		}
	}
	/*手机号、union_id、open_id 直接登录入口*/
	public function autologin($field,$value){
		$condition_user[$field] = $value;
		$now_user = $this->field(true)->where($condition_user)->find();
		if($now_user){
			if(empty($now_user['status'])){
				return array('error_code' => true, 'msg' => '该帐号被禁止登录!');
			}
			$condition_save_user['uid'] = $now_user['uid'];
			$data_save_user['last_time'] = $_SERVER['REQUEST_TIME'];
			$data_save_user['last_ip'] = get_client_ip(1);
			$this->where($condition_save_user)->data($data_save_user)->save();
			
			return array('error_code' => false, 'msg' => 'OK' ,'user'=>$now_user);
		}else{
			return array('error_code'=>1001,'msg'=>'没有此用户！');
		}
	}
	/*
	 *	提供用户信息注册用户，密码需要自行md5处理 
	 *
	 *	**** 请自行处理逻辑，此处直接插入用户表 ****
	 */
	public function autoreg($data_user){
		$data_user['add_time'] = $data_user['last_time'] = $_SERVER['REQUEST_TIME'];
		$data_user['add_ip'] = $data_user['last_ip'] = get_client_ip(1);
		if($data_user['openid']){
			$data_user['last_weixin_time'] = $_SERVER['REQUEST_TIME'];
		}
		if($this->data($data_user)->add()){
			return array('error_code' =>false,'msg' =>'OK');
		}else{
			return array('error_code' => true, 'msg' => '注册失败！请重试。');
		}
	}
	/*帐号密码注册*/
	public function checkreg($phone,$pwd){
		if (empty($phone)) {
			return array('error_code' => true, 'msg' => '手机号不能为空');
		}
		if (empty($pwd)) {
			return array('error_code' => true, 'msg' => '密码不能为空');
		}
		
		if(!preg_match('/^[0-9]{11}$/',$phone)){
			return array('error_code' => true, 'msg' => '请输入有效的手机号');
		}
			
		$condition_user['phone'] = $phone;
		if($this->field('`uid`')->where($condition_user)->find()){
			return array('error_code' => true, 'msg' => '手机号已存在');
		}
		
		$data_user['phone'] = $phone;
		$data_user['pwd'] = md5($pwd);
			
		$data_user['nickname'] = substr($phone,0,3).'****'.substr($phone,7);
		
		$data_user['add_time'] = $data_user['last_time'] = $_SERVER['REQUEST_TIME'];
		$data_user['add_ip'] = $data_user['last_ip'] = get_client_ip(1);
		
		if($this->data($data_user)->add()){
			$return = $this->checkin($phone,$pwd);
			
			if(empty($result['error_code'])){
    			return $return;
    		}else{
				return array('error_code' =>false,'msg' =>'OK');
			}
		}else{
			return array('error_code' => true, 'msg' => '注册失败！请重试。');
		}
	}
	/*修改用户信息*/
	public function save_user($uid,$field,$value){
		$condition_user['uid'] = $uid;
		$data_user[$field] = $value;
		if($this->where($condition_user)->data($data_user)->save()){
			return array('error'=>0,$field=>$value);
		}else{
			return array('error'=>1,'msg'=>'修改失败！请重试。');
		}
	}
	
	/*增加用户的钱*/
	public function add_money($uid,$money,$desc){
		$condition_user['uid'] = $uid;
		if($this->where($condition_user)->setInc('now_money',$money)){
			D('User_money_list')->add_row($uid,1,$money,$desc);
			return array('error_code' =>false,'msg' =>'OK');
		}else{
			return array('error_code' => true, 'msg' => '用户余额充值失败！请联系管理员协助解决。');
		}
	}
	
	/*使用用户的钱*/
	public function user_money($uid,$money,$desc){
		$condition_user['uid'] = $uid;
		if($this->where($condition_user)->setDec('now_money',$money)){
			D('User_money_list')->add_row($uid,2,$money,$desc);
			return array('error_code' =>false,'msg' =>'OK');
		}else{
			return array('error_code' => true, 'msg' => '用户余额扣除失败！请联系管理员协助解决。');
		}
	}
	
	
	/*增加用户的积分*/
	public function add_score($uid,$score,$desc){
		$condition_user['uid'] = $uid;
		if($this->where($condition_user)->setInc('score_count',$score)){
			D('User_score_list')->add_row($uid,1,$score,$desc);
			return array('error_code' =>false,'msg' =>'OK');
		}else{
			return array('error_code' => true, 'msg' => '添加积分失败！请联系管理员协助解决。');
		}
	}
	
	/*使用用户的积分*/
	public function user_score($uid,$score,$desc){
		$condition_user['uid'] = $uid;
		if($this->where($condition_user)->setDec('score_count',$score)){
			D('User_score_list')->add_row($uid,2,$score,$desc);
			return array('error_code' =>false,'msg' =>'OK');
		}else{
			return array('error_code' => true, 'msg' => '减少积分失败！请联系管理员协助解决。');
		}
	}
}
?>