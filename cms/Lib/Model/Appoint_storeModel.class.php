<?php
class Appoint_storeModel extends Model{
	
	/**
	 * 根据appoint_id获取所有的店铺
	 * @param int $appoint_id
	 */
	public function get_storelist_by_appointId($appoint_id){
		$store_list = D('')->table(array(C('DB_PREFIX').'appoint_store'=>'gc',C('DB_PREFIX').'merchant_store'=>'mc',C('DB_PREFIX').'area'=>'a'))->where("`gc`.`appoint_id`='$appoint_id' AND`gc`.`store_id`=`mc`.`store_id` AND `gc`.`area_id`=`a`.`area_id`")->order('`mc`.`store_id` ASC')->select();
		return $store_list;
	}
}

?>