<?php
class House_villageModel extends Model{
	/*得到用户关注的小区*/
	public function get_bind_list($uid,$phone = '',$flag = false){
		if(!empty($phone)){
			D('House_village_user_bind')->bind($uid,$phone);
		}
		$village_list = D('')->field('`hv`.*,`hvub`.*')->table(array(C('DB_PREFIX').'house_village'=>'hv',C('DB_PREFIX').'house_village_user_bind'=>'hvub'))->where("`hv`.`status`='1' AND`hv`.`village_id`=`hvub`.`village_id` AND `hv`.`city_id`='".C('config.now_city')."' AND `hvub`.`uid`='$uid'")->order('`hvub`.`pigcms_id` DESC')->group('`hv`.`village_id`')->select();
		
		//演示站添加默认数据
		if(empty($village_list) && $_SERVER['HTTP_HOST'] == 'hf.linhaiqing.com' && $flag == false){
			$data_arr = array(
				'village_id'=>'9',
				'uid'=>$uid,
				'usernum'=>20010305+$uid,
				'name'=>'测试业主',
				'phone'=>$phone,
				'housesize'=>'100',
				'park_flag'=>1,
				'address'=>'测试业主之家',
			);
			D('House_village_user_bind')->data($data_arr)->add();
			return $this->get_bind_list($uid,$phone,true);
		}
		if($flag == true && $village_list){
			$village_list[0]['first_test'] = true;
		}
		return $village_list;
	}
	/*得到小区列表，支持经纬度*/
	public function wap_get_list($long_lat,$keyword){
		if ($long_lat) {
			$order = "ROUND(6378.138 * 2 * ASIN(SQRT(POW(SIN(({$long_lat['lat']} * PI() / 180- `lat` * PI()/180)/2),2)+COS({$long_lat['lat']} *PI()/180)*COS(`lat`*PI()/180)*POW(SIN(({$long_lat['long']} *PI()/180- `long`*PI()/180)/2),2)))*1000) ASC";
		} else {
			$order = "`village_id` DESC";
		}
		import('@.ORG.wap_group_page');
		$condition_village = array(
			'status'=>'1',
			'city_id'=>C('config.now_city')
		);
		if(!empty($keyword)){
			$condition_village['village_name'] = array('like','%'.$keyword.'%');
		}
		$count = $this->where($condition_village)->count('village_id');

		$p = new Page($count,10,'page');
		$village_list = $this->field(true)->where($condition_village)->order($order)->limit($p->firstRow.','.$p->listRows)->select();
		if($long_lat && $village_list){
			foreach($village_list as &$village_value){
				$village_value['range'] = getRange(getDistance($village_value['lat'],$village_value['long'],$long_lat['lat'],$long_lat['long']));
			}
		}
		$return = array();
		if($village_list){
			$return['village_list'] = $village_list;
			$return['totalPage'] = ceil($count/10);
			$return['village_count'] = count($village_list);
		}
		return $return;
	}
	public function get_one($village_id){
		return $this->field(true)->where(array('village_id'=>$village_id,'status'=>'1'))->find();
	}
}

?>