<?php
class House_village_groupModel extends Model{
	/*得到小区绑定的团购列表*/
	public function get_limit_list($village_id,$limit,$user_long_lat){
		
		$now_time = $_SERVER['REQUEST_TIME'];
		
		$group_list = D('')->field('`g`.`name` AS `group_name`,`m`.`name` AS `merchant_name`,`g`.*,`m`.*')->table(array(C('DB_PREFIX').'group'=>'g',C('DB_PREFIX').'merchant'=>'m',C('DB_PREFIX').'house_village_group'=>'hvg'))->where("`g`.`mer_id`=`m`.`mer_id` AND `g`.`status`='1' AND `m`.`status`='1' AND `g`.`type`='1' AND `g`.`begin_time`<'$now_time' AND `g`.`end_time`>'$now_time' AND `hvg`.`group_id`=`g`.`group_id` AND `hvg`.`village_id`='$village_id'")->order('`hvg`.`sort` DESC,`hvg`.`pigcms_id` DESC')->limit($limit)->select();
		if($group_list){
			$group_image_class = new group_image();
			
			foreach($group_list as $key=>$value){
				unset($group_list[$key]['content'],$group_list[$key]['txt_info'],$group_list[$key]['cue']);
				$tmp_pic_arr = explode(';',$value['pic']);
				$group_list[$key]['list_pic'] = $group_image_class->get_image_by_path($tmp_pic_arr[0],'s');
				$group_list[$key]['url'] = D('Group')->get_group_url($value['group_id'],true);
				$group_list[$key]['price'] = floatval($value['price']);
				$group_list[$key]['old_price'] = floatval($value['old_price']);
				$group_list[$key]['wx_cheap'] = floatval($value['wx_cheap']);
			}
			
			if($user_long_lat){
				$group_store_database = D('Group_store');
				$rangeSort = array();
				foreach($group_list as &$storeGroupValue){
					$tmpStoreList = $group_store_database->get_storelist_by_groupId($storeGroupValue['group_id']);
					if($tmpStoreList){
						foreach($tmpStoreList as &$tmpStore){
							$tmpStore['Srange'] = getDistance($user_long_lat['lat'],$user_long_lat['long'],$tmpStore['lat'],$tmpStore['long']);
							$tmpStore['range'] = getRange($tmpStore['Srange'],false);
							$rangeSort[] = $tmpStore['Srange'];
						}
						array_multisort($rangeSort, SORT_ASC, $tmpStoreList);
						$storeGroupValue['store_list'] = $tmpStoreList;	
						$storeGroupValue['range'] = $tmpStoreList[0]['range'];
					}
				}
			}
			return $group_list;
		}else{
			return false;
		}
		
	}
	
	/*得到小区绑定的团购列表(有分页)*/
	public function get_limit_list_page($village_id,$pageSize=20,$user_long_lat = array()){
	
		$now_time = $_SERVER['REQUEST_TIME'];
		$condition_table = array(C('DB_PREFIX').'group'=>'g',C('DB_PREFIX').'merchant'=>'m',C('DB_PREFIX').'house_village_group'=>'hvg');
		$condition_field = "`g`.`name` AS `group_name`,`m`.`name` AS `merchant_name`,`g`.*,`m`.*,`hvg`.`sort`";
		$condition_where = "`g`.`mer_id`=`m`.`mer_id` AND `g`.`status`='1' AND `m`.`status`='1' AND `g`.`type`='1' AND `g`.`begin_time`<'$now_time' AND `g`.`end_time`>'$now_time' AND `hvg`.`group_id`=`g`.`group_id` AND `hvg`.`village_id`='$village_id'";
		
		$count_group = D('')->table($condition_table)->where($condition_where)->count();
		import('@.ORG.merchant_page');
		$p = new Page($count_group,$pageSize,'page');
		$group_list = D('')->field($condition_field)->table($condition_table)->where($condition_where)->order('`hvg`.`sort` DESC,`hvg`.`pigcms_id` DESC')->limit($p->firstRow.','.$p->listRows)->select();
		if($group_list){
			$group_image_class = new group_image();
				
			foreach($group_list as $key=>$value){
				unset($group_list[$key]['content'],$group_list[$key]['txt_info'],$group_list[$key]['cue']);
				$tmp_pic_arr = explode(';',$value['pic']);
				$group_list[$key]['list_pic'] = $group_image_class->get_image_by_path($tmp_pic_arr[0],'s');
				$group_list[$key]['url'] = D('Group')->get_group_url($value['group_id'],$is_wap);
				$group_list[$key]['price'] = floatval($value['price']);
				$group_list[$key]['old_price'] = floatval($value['old_price']);
				$group_list[$key]['wx_cheap'] = floatval($value['wx_cheap']);
			}
			
			if($user_long_lat){
				$group_store_database = D('Group_store');
				foreach($group_list as &$storeGroupValue){
					$tmpStoreList = $group_store_database->get_storelist_by_groupId($storeGroupValue['group_id']);
					if($tmpStoreList){
						foreach($tmpStoreList as &$tmpStore){
							$tmpStore['Srange'] = getDistance($user_long_lat['lat'],$user_long_lat['long'],$tmpStore['lat'],$tmpStore['long']);
							$tmpStore['range'] = getRange($tmpStore['Srange'],false);
						}
						$storeGroupValue['store_list'] = $tmpStoreList;	
						$storeGroupValue['range'] = $tmpStoreList[0]['range'];
					}
				}
			}
			$return = array();
			if($group_list){
				$return['totalPage'] = ceil($count_group/$pageSize);
				$return['group_count'] = count($group_list);
				$return['pagebar'] = $p->show();
				$return['group_list'] = $group_list;
			}
			
			return $return;	
		}else{
			return false;
		}
	
	}
}

?>