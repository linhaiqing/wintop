<?php
class MerchantModel extends Model{
	public function get_info($id){
		$now_merchant = $this->field(true)->where(array('mer_id'=>$id))->find();
		if(empty($now_merchant)){
			return false;
		}
		return $now_merchant;
	}
	public function get_qrcode($id){
		$now_merchant = $this->field('`mer_id`,`qrcode_id`')->where(array('mer_id'=>$id))->find();
		if(empty($now_merchant)){
			return false;
		}
		return $now_merchant;
	}
	public function save_qrcode($id,$qrcode_id){
		if($this->where(array('mer_id'=>$id))->data(array('qrcode_id'=>$qrcode_id))->save()){
			return(array('error_code'=>false));
		}else{
			return(array('error_code'=>true,'msg'=>'保存二维码至商家信息失败！请重试。'));
		}
	}
	public function del_qrcode($id){
		if($this->where(array('mer_id'=>$id))->data(array('qrcode_id'=>''))->save()){
			return(array('error_code'=>false));
		}else{
			return(array('error_code'=>true,'msg'=>'保存二维码至商家信息失败！请重试。'));
		}
	}
	/*
	 * 若用户扫描了商家二维码，则为商户储存首页排序值。 若商家设置了自动增长的团购ID，则自动增长某团购。
	 *
	 */
	public function update_group_indexsort($mer_id){
		if(empty($mer_id)) return false;
		$now_merchant = $this->field('`auto_indexsort_groupid`')->where(array('mer_id'=>$mer_id))->find();
		if(empty($now_merchant)){
			return false;
		}
		$merchant_qrcode_indexsort = C('config.merchant_qrcode_indexsort');
		if($now_merchant['auto_indexsort_groupid']){
			$database_group = D('Group');
			$condition_group['group_id'] = $now_merchant['auto_indexsort_groupid'];
			if($database_group->where($condition_group)->setInc('index_sort',$merchant_qrcode_indexsort)){
				return true;
			}
		}
		$this->where(array('mer_id'=>$mer_id))->setInc('storage_indexsort',$merchant_qrcode_indexsort);
	}
	
	public function get_merchants_by_long_lat($lat, $long, $around_range = 2000)
	{
		import('@.ORG.longlat');
		$longlat_class = new longlat();
 		$location2 = $longlat_class->gpsToBaidu($lat, $long);//转换腾讯坐标到百度坐标
		$lat = $location2['lat'];
		$long = $location2['lng'];
	    		
		$Model = new Model();
		$sql = "SELECT s.lat, s.long, s.mer_id, s.name as sname, s.store_id, m.name, m.phone, s.adress, m.pic_info, ROUND(6378.138 * 2 * ASIN(SQRT(POW(SIN(({$lat}*PI()/180-`s`.`lat`*PI()/180)/2),2)+COS({$lat}*PI()/180)*COS(`s`.`lat`*PI()/180)*POW(SIN(({$long}*PI()/180-`s`.`long`*PI()/180)/2),2)))*1000) as juli FROM ". C('DB_PREFIX') . "merchant_store AS s INNER JOIN ". C('DB_PREFIX') . "merchant AS m ON s.mer_id=m.mer_id WHERE `s`.`status`=1 AND ROUND(6378.138 * 2 * ASIN(SQRT(POW(SIN(({$lat}*PI()/180-`s`.`lat`*PI()/180)/2),2)+COS({$lat}*PI()/180)*COS(`s`.`lat`*PI()/180)*POW(SIN(({$long}*PI()/180-`s`.`long`*PI()/180)/2),2)))*1000) < '$around_range' ORDER BY juli ASC";
		$result = $Model->query($sql);
		$merchant_image_class = new merchant_image();
		foreach ($result as &$r) {
			$images = explode(";", $r['pic_info']);
			$images = explode(";", $images[0]);
			$r['img'] = $merchant_image_class->get_image_by_path($images[0]);
			$r['url'] = C('config.site_url').'/wap.php?c=Index&a=index&token=' . $r['mer_id'];
		}
		return $result;
	}

	/*收藏列表*/
	public function get_collect_list($uid){
		$now_time = $_SERVER['REQUEST_TIME'];
		$condition_where = "`m`.`mer_id`=`c`.`id` AND `c`.`type`='merchant_id' AND `c`.`uid`='{$uid}'";
		$condition_table = array(C('DB_PREFIX').'merchant'=>'m',C('DB_PREFIX').'user_collect'=>'c');
		$condition_field  = '`m`.*,c.collect_id';
	
		$order = '`c`.`collect_id` DESC';
	
		import('@.ORG.collect_page');
		$count_meal = D('')->table($condition_table)->where($condition_where)->count();
		$p = new Page($count_group,10,'page');
		$merchant_list = D('')->field($condition_field)->table($condition_table)->where($condition_where)->order($order)->limit($p->firstRow.','.$p->listRows)->select();
	
		$return['pagebar'] = $p->show();
	
		if($merchant_list){
			$store_image_class = new merchant_image();
			foreach($merchant_list as &$v){
				$images = $store_image_class->get_allImage_by_path($v['pic_info']);
				$v['list_pic'] = $images ? array_shift($images) : array();
				$v['url'] = C('config.site_url').'/merindex/'.$v['mer_id'].'.html';
			}
		}
		$return['merchant_list'] = $merchant_list;
	
		return $return;
	}
}

?>