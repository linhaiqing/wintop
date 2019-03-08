<?php
class User_money_listModel extends Model{
	/*增加记录行数*/
	public function add_row($uid,$type,$money,$msg,$record_ip = true){
		$data_user_money_list['uid'] = $uid;
		$data_user_money_list['type'] = $type;
		$data_user_money_list['money'] = $money;
		$data_user_money_list['desc'] = $msg;
		$data_user_money_list['time'] = $_SERVER['REQUEST_TIME'];
		if($record_ip){
			$data_user_money_list['ip'] = get_client_ip(1);
		}
		if($this->data($data_user_money_list)->add()){
			return true;
		}else{
			return false;
		}
	}
	/*获取列表*/
	public function get_list($uid){
		$condition_user_money_list['uid'] = $uid;
		
		import('@.ORG.user_page');
		$count = $this->where($condition_user_money_list)->count();
		$p = new Page($count,10);
		
		$return['money_list'] = $this->field(true)->where($condition_user_money_list)->order('`time` DESC')->limit($p->firstRow.',10')->select();
		$return['pagebar'] = $p->show();
		return $return;
	}
}
?>