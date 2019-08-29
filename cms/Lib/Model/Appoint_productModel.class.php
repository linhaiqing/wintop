<?php
/**
 * 添加预约，自定义字段
 * pigcms_appoint_product
 */
class Appoint_productModel extends Model{
	/*获取预约下的所有的产品项目*/
	public function get_productlist_by_appointId($appoint_id){
		$database_custom = D('Appoint_product');
		$where['appoint_id'] = $appoint_id;
		$custom_list = $database_custom->field(true)->where($where)->select();
		return $custom_list;
	}
	 
}