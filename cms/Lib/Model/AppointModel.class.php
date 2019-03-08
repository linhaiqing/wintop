<?php
class AppointModel extends Model{
	
	/*wap版得到指定分类ID或分类父ID下的分类，带有分页功能*/
	public function wap_get_appoint_list_by_catid($get_grouplist_catid,$get_grouplist_catfid,$area_id,$order, $lat = 0, $long = 0, $circle_id = 0){
		$now_time = $_SERVER['REQUEST_TIME'];
		$condition_where = "`m`.`city_id`='".C('config.now_city')."' AND `g`.`mer_id`=`m`.`mer_id` AND `g`.`check_status`='1' AND `g`.`appoint_status`='0' AND `m`.`status`='1' AND `g`.`start_time`<'$now_time' AND `g`.`end_time`>'$now_time'";
		//分类相关
		if(!empty($get_grouplist_catfid)){
			$condition_where .= " AND `g`.`cat_fid`='$get_grouplist_catfid'";
		}else if(!empty($get_grouplist_catid)){
			$condition_where .= " AND `g`.`cat_id`='$get_grouplist_catid'";
		}
	
		// 区域相关
		if($area_id || $circle_id){
			$condition_field  = 'DISTINCT `g`.`appoint_id`,`m`.`name` AS `merchant_name`,`g`.*,`m`.*';
			if ($circle_id) {
				$condition_where .= " AND `gc`.`circle_id`='$circle_id' AND `gc`.`appoint_id`=`g`.`appoint_id`";
			} else {
				$condition_where .= " AND `gc`.`area_id`='$area_id' AND `gc`.`appoint_id`=`g`.`appoint_id`";
			}
			if ($order == 'juli') {
				$condition_table  = array(C('DB_PREFIX').'appoint'=>'g',C('DB_PREFIX').'merchant'=>'m',C('DB_PREFIX').'group_store'=>'gc', C('DB_PREFIX').'merchant_store'=>'s');
				$condition_where .= " AND `m`.`mer_id`=`s`.`mer_id`";
				$condition_field .= ", ROUND(6378.138 * 2 * ASIN(SQRT(POW(SIN(({$lat}*PI()/180-`s`.`lat`*PI()/180)/2),2)+COS({$lat}*PI()/180)*COS(`s`.`lat`*PI()/180)*POW(SIN(({$long}*PI()/180-`s`.`long`*PI()/180)/2),2)))*1000) AS juli";
			} else {
				$condition_table  = array(C('DB_PREFIX').'appoint'=>'g',C('DB_PREFIX').'merchant'=>'m',C('DB_PREFIX').'group_store'=>'gc');
			}
			
			
		}else{
			$condition_field  = ' `m`.`name` AS `merchant_name`,`g`.*,`m`.*';
			if ($order == 'juli') {
				$condition_table = array(C('DB_PREFIX').'appoint'=>'g',C('DB_PREFIX').'merchant'=>'m',C('DB_PREFIX').'merchant_store'=>'s');
				$condition_where .= " AND `m`.`mer_id`=`s`.`mer_id`";
				$condition_field .= ", ROUND(6378.138 * 2 * ASIN(SQRT(POW(SIN(({$lat}*PI()/180-`s`.`lat`*PI()/180)/2),2)+COS({$lat}*PI()/180)*COS(`s`.`lat`*PI()/180)*POW(SIN(({$long}*PI()/180-`s`.`long`*PI()/180)/2),2)))*1000) AS juli";
			} else {
				$condition_table = array(C('DB_PREFIX').'appoint'=>'g',C('DB_PREFIX').'merchant'=>'m');
			}
		}
		
		//排序相关
		switch($order){
			case 'price':
				$order = '`g`.`appoint_price` ASC,`g`.`appoint_id` DESC';
				break;
			case 'priceDesc':
				$order = '`g`.`appoint_price` DESC,`g`.`appoint_id` DESC';
				break;
			case 'start':
				$order = '`g`.`create_time` DESC,`g`.`appoint_id` DESC';
				break;
			case 'juli':
				$order = 'juli asc,`g`.`appoint_id` DESC';
				break;
			case 'appointNum':
				$order = '`g`.`appoint_sum` DESC,`g`.`appoint_id` DESC';
				break;
			default:
				$order = '`g`.`sort` DESC,`g`.`appoint_id` DESC';
		}
		
		import('@.ORG.wap_group_page');
		$count_group = D('')->table($condition_table)->where($condition_where)->count('DISTINCT `g`.`appoint_id`');

		$p = new Page($count_group,C('config.appoint_page_row'),C('config.appoint_page_val'));
		$group_list = D('')->field($condition_field)->table($condition_table)->where($condition_where)->order($order)->group('`g`.`appoint_id`, `m`.`mer_id`')->limit($p->firstRow.','.$p->listRows)->select();
		$return['pagebar'] = $count_group>0?$p->show():'';
	
		if($group_list){
			$group_image_class = new appoint_image();
			foreach($group_list as $k=>$v){
				$tmp_pic_arr = explode(';',$v['pic']);
				$group_list[$k]['list_pic'] = $group_image_class->get_image_by_path($tmp_pic_arr[0],'s');
				$group_list[$k]['url'] = $this->get_appoint_url($v['appoint_id'],true);
				$group_list[$k]['payment_money'] = floatval($v['payment_money']);
				$group_list[$k]['appoint_sum'] = intval($v['appoint_sum']);
			}
		} 
		$return['group_list'] = $group_list;
		$return['totalPage'] = $count_group>0?$p->totalPage:'';
		$return['meal_count'] = $count_group;
		return $return;
	}
	
	// wap用
	public function get_list_by_search($w, $order = 'store_id',$is_wap = false){
		$now_time = $_SERVER['REQUEST_TIME'];
		$condition_table = array(C('DB_PREFIX').'appoint'=>'g',C('DB_PREFIX').'merchant'=>'m');
		$condition_where = "`g`.`mer_id`=`m`.`mer_id` AND `g`.`check_status`='1' AND `g`.`appoint_status`='0' AND `m`.`status`='1' AND `g`.`start_time`<'$now_time' AND `g`.`end_time`>'$now_time'";
		
		//排序相关
		switch($order){
			case 'price':
				$order = '`g`.`appoint_price` ASC,`g`.`appoint_id` DESC';
				break;
			case 'priceDesc':
				$order = '`g`.`appoint_price` DESC,`g`.`appoint_id` DESC';
				break;
			case 'start':
				$order = '`g`.`create_time` DESC,`g`.`appoint_id` DESC';
				break;
			case 'juli':
				$order = 'juli asc,`g`.`appoint_id` DESC';
				break;
			case 'appointNum':
				$order = '`g`.`appoint_sum` DESC,`g`.`appoint_id` DESC';
				break;
			default:
				$order = '`g`.`sort` DESC,`g`.`appoint_id` DESC';
		}
		if($w){
			$condition_where .= " AND `g`.`appoint_name` like '%$w%' ";
		}
		if(empty($is_wap)){
			import('@.ORG.group_page');
		}else{
			import('@.ORG.wap_group_search_page');
		}

		$count_group = D('')->table($condition_table)->where($condition_where)->count('DISTINCT `g`.`appoint_id`');
		C('config.appoint_page_row',10);
		$p = new Page($count_group,C('config.appoint_page_row'),C('config.appoint_page_val'));
		$group_list = D('')->field($condition_field)->table($condition_table)->where($condition_where)->order($order)->group('`g`.`appoint_id`, `m`.`mer_id`')->limit($p->firstRow.','.$p->listRows)->select();

		$return['pagebar'] = $count_group>0?$p->show():'';
		
		if(count($group_list)>0){
			$group_image_class = new appoint_image();
			foreach($group_list as $k=>$v){
				$tmp_pic_arr = explode(';',$v['pic']);
				$group_list[$k]['list_pic'] = $group_image_class->get_image_by_path($tmp_pic_arr[0],'s');
				$group_list[$k]['url'] = $this->get_appoint_url($v['appoint_id'],true);
				$group_list[$k]['payment_money'] = floatval($v['payment_money']);
				$group_list[$k]['appoint_sum'] = intval($v['appoint_sum']);
			}
		}
		$return['group_list'] = $count_group>0?$group_list:'';
		$return['totalPage'] = $count_group>0?$p->totalPage:'';
		$return['meal_count'] = $count_group;
		$return['group_count'] = $count_group;
		$return['store_count'] = $count_group;
		
		return $return;
	}
	
	/*得到指定分类ID或分类父ID下的分类，带有分页功能*/
	public function get_appoint_list_by_catid($get_appointlist_catid,$get_grouplist_catfid,$cat_url,$area_id,$circle_id,$order,$attrs,$category_cat_field){
		
		$now_time = $_SERVER['REQUEST_TIME'];
		$condition_where = "`m`.`city_id`='".C('config.now_city')."' AND `g`.`mer_id`=`m`.`mer_id` AND `g`.`check_status`='1' AND `g`.`appoint_status`='0' AND `m`.`status`='1' AND `g`.`start_time`<'$now_time' AND `g`.`end_time`>'$now_time'";
		//分类相关
		if(!empty($get_grouplist_catfid)){
			$condition_where .= " AND `g`.`cat_fid`='$get_appointlist_catid'";
		}else if(!empty($get_appointlist_catid)){
			$condition_where .= " AND `g`.`cat_id`='$get_appointlist_catid'";
		}

		//区域或商圈
		if($circle_id){
			$condition_where .= " AND `gc`.`circle_id`='$circle_id' AND `gc`.`appoint_id`=`g`.`appoint_id`";
			$condition_table  = array(C('DB_PREFIX').'appoint'=>'g',C('DB_PREFIX').'merchant'=>'m',C('DB_PREFIX').'group_store'=>'gc');
			$condition_field  = 'DISTINCT `g`.`appoint_id`,`g`.`appoint_name` AS `appoint_name`,`g`.`hits` AS `appoint_hits`,`m`.`name` AS `merchant_name`,`g`.*,`m`.*';
		}else if($area_id){
			$condition_where .= " AND `gc`.`area_id`='$area_id' AND `gc`.`group_id`=`g`.`group_id`";
			$condition_table  = array(C('DB_PREFIX').'appoint'=>'g',C('DB_PREFIX').'merchant'=>'m',C('DB_PREFIX').'group_store'=>'gc');
			$condition_field  = 'DISTINCT `g`.`group_id`,`g`.`appoint_name` AS `appoint_name`,`g`.`hits` AS `appoint_hits`,`m`.`name` AS `merchant_name`,`g`.*,`m`.*';
		}else{
			$condition_table = array(C('DB_PREFIX').'appoint'=>'g',C('DB_PREFIX').'merchant'=>'m');
			$condition_field  = '`g`.`appoint_name` AS `appoint_name`,`g`.`hits` AS `appoint_hits`,`m`.`name` AS `merchant_name`,`g`.*,`m`.*';
		}
		
		//自定义字段
		if(!empty($attrs)){
			$attrs_tmp_arr_old = explode(';',$attrs);
			if(!empty($attrs_tmp_arr_old)){
				foreach($attrs_tmp_arr_old as $key=>$value){
					$attrs_tmp_str = explode(':',$value);
					$attrs_arr[$attrs_tmp_str[0]] = $attrs_tmp_str[1];
				}
			}
			$cat_field_arr = unserialize($category_cat_field);
			foreach($cat_field_arr as $key=>$value){
				if(empty($value['use_field']) && isset($attrs_arr[$value['url']])){
					if($value['type'] == 0){
						$tmp_custom_value = $attrs_arr[$value['url']];
						$condition_where .= ' AND `g`.`custom_'.$key."`='$tmp_custom_value'";
					}else if($value['type'] == 1){
						$tmp_custom_value = $attrs_arr[$value['url']];
						$tmp_custom_arr = explode(',',$tmp_custom_value);
						foreach($tmp_custom_arr as $k=>$v){
							$condition_where .= " AND FIND_IN_SET('$v',`g`.`custom_".$key."`)";
						}
						
					}
				}
			}
		}
		
		//排序相关
		switch($order){
			case 'hit-asc':
				$order = '`appoint_hits` ASC,`g`.`appoint_id` DESC';
				break;
			case 'hit-desc':
				$order = '`appoint_hits` DESC,`g`.`appoint_id` DESC';
				break;
			case 'time':
				$order = '`g`.`create_time` DESC,`g`.`appoint_id` DESC';
				break;
			case 'hot':
				$order = '`g`.`appoint_sum` DESC,`g`.`appoint_id` DESC';
				break;
			default:
				$order = '`g`.`sort` DESC,`g`.`appoint_id` DESC';
		}

		import('@.ORG.group_page');
		$count_group = D('')->table($condition_table)->where($condition_where)->count();
		$p = new Page($count_group,C('config.appoint_page_row'),C('config.appoint_page_val'));
		$group_list = D('')->field($condition_field)->table($condition_table)->where($condition_where)->order($order)->limit($p->firstRow.','.$p->listRows)->select();
//echo D('')->_sql();	
		$return['pagebar'] = $p->show();
		
		if($group_list){
			$group_image_class = new appoint_image();
			foreach($group_list as $k=>$v){
				$tmp_pic_arr = explode(';',$v['pic']);
				$group_list[$k]['list_pic'] = $group_image_class->get_image_by_path($tmp_pic_arr[0],'s');
				$group_list[$k]['url'] = $this->get_appoint_url($v['appoint_id']);
				$group_list[$k]['payment_money'] = floatval($v['payment_money']);
				$group_list[$k]['appoint_sum'] = intval($v['appoint_sum']);
			}
		}
		$return['group_list'] = $group_list;
		
		return $return;
	}
	
	public function get_order_list($uid, $status, $is_wap=false){
		$database_appoint = D('Appoint');
		$database_appoint_order = D('Appoint_order');
		$condition_where['uid'] = $uid;
		$condition_where['paid'] = array('neq', 4);
		if($status == '1'){
			$condition_where['service_status'] = '0';
		}
		if($status == '2'){
			$condition_where['service_status'] = '1';
		}
		
		$order_list = $database_appoint_order->field(true)->where($condition_where)->order('`order_time` DESC, `order_id` DESC')->select();
		if(!empty($order_list)){
			$appoint_image_class = new appoint_image();
			foreach($order_list as $k=>$v){
				$where['appoint_id'] = $v['appoint_id'];
				$appoint_info = $database_appoint->field('`pic`, `appoint_name`, `appoint_price`, `payment_status`')->where($where)->find();
				$tmp_pic_arr = explode(';', $appoint_info['pic']);
				$order_list[$k]['list_pic'] = $appoint_image_class->get_image_by_path($tmp_pic_arr[0],'s');
				$order_list[$k]['url'] = $this->get_appoint_url($v['appoint_id'],true);
				$order_list[$k]['payment_money'] = floatval($v['payment_money']);
				$order_list[$k]['order_url'] = $database_appoint_order->get_order_url($v['order_id'], true);
				$order_list[$k]['appoint_name'] = $appoint_info['appoint_name'];
				$order_list[$k]['payment_status'] = $appoint_info['payment_status'];
				$order_list[$k]['appoint_price'] = floatval($appoint_info['appoint_price']);
			}
		}
		
		return $order_list;
	}
	
	public function wap_get_order_list($uid, $status = '0'){
		$database_appoint = D('Appoint');
		$database_appoint_order = D('Appoint_order');
		$condition_where['uid'] = $uid;
		$condition_where['paid'] = array('neq', 4);
		if($status == '1'){
			$condition_where['service_status'] = '0';
		}
		if($status == '2'){
			$condition_where['service_status'] = '1';
		}
		
		$order_list = $database_appoint_order->field(true)->where($condition_where)->order('`order_time` DESC')->select();
		if(!empty($order_list)){
			$appoint_image_class = new appoint_image();
			foreach($order_list as $k=>$v){
				$where['appoint_id'] = $v['appoint_id'];
				$appoint_info = $database_appoint->field('`pic`, `appoint_name`, `appoint_price`, `payment_status`')->where($where)->find();
				$tmp_pic_arr = explode(';', $appoint_info['pic']);
				$order_list[$k]['list_pic'] = $appoint_image_class->get_image_by_path($tmp_pic_arr[0],'s');
				$order_list[$k]['url'] = $this->get_appoint_url($v['appoint_id'],true);
				$order_list[$k]['payment_money'] = floatval($v['payment_money']);
				$order_list[$k]['order_url'] = $database_appoint_order->get_order_url($v['order_id'], true);
				$order_list[$k]['appoint_name'] = $appoint_info['appoint_name'];
				$order_list[$k]['payment_status'] = $appoint_info['payment_status'];
				$order_list[$k]['appoint_price'] = floatval($appoint_info['appoint_price']);
			}
		}
		
		return $order_list;
	}
	
	/**
	 * 通过appoint_id获得该预约的相关信息
	 * @param int $appoint_id
	 * @param string $other
	 */
	public function get_appoint_by_appointId($appoint_id,$other=''){
		$condition_where = "`g`.`mer_id`=`m`.`mer_id` AND `m`.`status`='1' AND `g`.`appoint_status`='0' AND `g`.`check_status`='1' AND `g`.`appoint_id`='$appoint_id'";
		$condition_table = array(C('DB_PREFIX').'appoint'=>'g',C('DB_PREFIX').'merchant'=>'m');
		$condition_field  = '`g`.`appoint_name` AS `appoint_name`,`g`.`hits` AS `appoint_hits`,`m`.`name` AS `merchant_name`,`g`.*,`m`.*';
		$database = D('');	
		$now_group = D('')->field($condition_field)->table($condition_table)->where($condition_where)->find();
		if(!empty($now_group)){
			$group_image_class = new appoint_image();
			$now_group['list_pic'] = $group_image_class->get_image_by_path($tmp_pic_arr[0],'s');
			$now_group['url'] = C('config.site_url').'/appoint/'.$now_group['appoint_id'].'.html';
			$now_group['payment_money'] = floatval($now_group['payment_money']);
			$now_group['appoint_sum'] = intval($now_group['appoint_sum']);
			$now_group['buy_url'] = C('config.site_url').'/appoint/order/'.$now_group['appoint_id'].'.html';
			$now_group['all_pic'] = $group_image_class->get_allImage_by_path($now_group['pic']);
			
			$now_group['store_list'] = D('Appoint_store')->get_storelist_by_appointId($now_group['appoint_id']);
			if(count($now_group['store_list']) == 1){
				$now_group['store_list'][0]['area'] = D('Area')->get_area_by_areaId($now_group['store_list'][0]['area_id']);
				$now_group['store_list'][0]['circle'] = D('Area')->get_area_by_areaId($now_group['store_list'][0]['circle_id']);
			}
			if($other){
				$condition_group['appoint_id'] = $appoint_id;
				switch($other){
					case 'hits-setInc':
						$this->where($condition_group)->setInc('hits');
						break;
				}
			}
		}

		return $now_group;
	}
	
	public function get_appointlist_by_MerchantId($mer_id,$limit=0,$is_wap=false,$appoint_id=0){
		$now_time = $_SERVER['REQUEST_TIME'];
		$condition_where = "`g`.`mer_id`=`m`.`mer_id` AND `m`.`status`='1' AND `g`.`appoint_status`='0' AND `g`.`check_status`='1'  AND `g`.`start_time`<'$now_time' AND `g`.`end_time`>'$now_time' AND `g`.`mer_id`='$mer_id'";
		if(!empty($appoint_id)){
			$condition_where .= " AND `g`.`appoint_id`<>'$appoint_id'";
		}
		$group_list = D('')->field('`g`.`appoint_name` AS `appoint_name`,`m`.`name` AS `merchant_name`,`g`.*,`m`.*')->table(array(C('DB_PREFIX').'appoint'=>'g',C('DB_PREFIX').'merchant'=>'m'))->where($condition_where)->order('`g`.`sort` DESC,`g`.`appoint_id` DESC')->limit($limit)->select();
		if($group_list){
			$group_image_class = new appoint_image();
			foreach($group_list as $k=>$v){
				$tmp_pic_arr = explode(';',$v['pic']);
				$group_list[$k]['list_pic'] = $group_image_class->get_image_by_path($tmp_pic_arr[0],'s');
				$group_list[$k]['url'] = $this->get_appoint_url($v['appoint_id'],$is_wap);
				$group_list[$k]['payment_money'] = floatval($v['payment_money']);
				$group_list[$k]['appoint_sum'] = intval($v['appoint_sum']);
			}
		}
		return $group_list;
	}
	
	/*得到指定分店的团购*/
	public function get_store_appoint_list($store_id,$limit=6,$is_wap=false){
		$now_time = $_SERVER['REQUEST_TIME'];
		$group_list = D('')->field('`g`.`appoint_name` AS `appoint_name`,`m`.`name` AS `merchant_name`,`g`.*,`gc`.*,`m`.*')->table(array(C('DB_PREFIX').'appoint'=>'g',C('DB_PREFIX').'merchant'=>'m',C('DB_PREFIX').'appoint_store'=>'gc'))->where("`g`.`appoint_id`=`gc`.`appoint_id` AND `gc`.`store_id`='$store_id' AND `g`.`mer_id`=`m`.`mer_id` AND `g`.`appoint_status`='0' AND `g`.`check_status`='1'  AND `g`.`start_time`<'$now_time' AND `g`.`end_time`>'$now_time' AND `m`.`status`='1'")->order('`g`.`sort` DESC,`g`.`appoint_id` DESC')->limit($limit)->select();
		if($group_list){
			$group_image_class = new appoint_image();
			
			foreach($group_list as $key=>$value){
				$tmp_pic_arr = explode(';',$value['pic']);
				$group_list[$key]['list_pic'] = $group_image_class->get_image_by_path($tmp_pic_arr[0],'s');
				$group_list[$key]['url'] = $this->get_appoint_url($value['appoint_id'],$is_wap);
				$group_list[$key]['payment_money'] = floatval($value['payment_money']);
				$group_list[$key]['appoint_sum'] = intval($value['appoint_sum']);
			}
			return $group_list;
		}else{
			return false;
		}
	}
	
	/*得到分类下的团购*/
	public function get_appointlist_by_catId($cat_id,$cat_fid,$limit=6,$is_wap=false,$sort=''){
		$now_time = $_SERVER['REQUEST_TIME'];
		$condition_where = "`g`.`mer_id`=`m`.`mer_id` AND `m`.`status`='1' AND `g`.`appoint_status`='0' AND `g`.`check_status`='1'  AND `g`.`start_time`<'$now_time' AND `g`.`end_time`>'$now_time'";
		if(empty($cat_fid) && !empty($cat_id)){
			$condition_where .= " AND `g`.`cat_fid`='$cat_id'";
		}else if(!empty($cat_id)){
			$condition_where .= " AND `g`.`cat_id`='$cat_id'";
		}
		if(empty($sort)){
			$condition_sort = "'`g`.`sort` DESC,`g`.`appoint_id` DESC'";
		}else{
			$condition_sort = $sort;
		}
		
		$group_list = D('')->field('`g`.`appoint_name` AS `appoint_name`,`m`.`name` AS `merchant_name`,`g`.*,`m`.*')->table(array(C('DB_PREFIX').'appoint'=>'g',C('DB_PREFIX').'merchant'=>'m'))->where($condition_where)->order($condition_sort)->limit($limit)->select();
		
		if($group_list){
			$group_image_class = new appoint_image();
			foreach($group_list as $k=>$v){
				$tmp_pic_arr = explode(';',$v['pic']);
				$group_list[$k]['list_pic'] = $group_image_class->get_image_by_path($tmp_pic_arr[0],'s');
				$group_list[$k]['url'] = $this->get_appoint_url($v['appoint_id'],$is_wap);
				$group_list[$k]['payment_money'] = floatval($v['payment_money']);
				$group_list[$k]['appoint_sum'] = intval($v['appoint_sum']);
			}
		}
		return $group_list;
	}
	
	// 二维码
	public function get_qrcode($id){
		$condition_group['appoint_id'] = $id;
		$now_group = $this->field('`appoint_id`,`qrcode_id`')->where($condition_group)->find();
		if(empty($now_group)){
			return false;
		}
		return $now_group;
	}
	public function save_qrcode($id,$qrcode_id){
		$condition_group['appoint_id'] = $id;
		$data_group['qrcode_id'] = $qrcode_id;
		if($this->where($condition_group)->data($data_group)->save()){
			return(array('error_code'=>false));
		}else{
			dump($this);
			return(array('error_code'=>true,'msg'=>'保存二维码至预约失败！请重试。'));
		}
	}
	
	public function get_appoint_url($appoint_id,$is_wap){
		if($is_wap){
			return U('Wap/Appoint/detail',array('appoint_id'=>$appoint_id));
		}else{
			return C('config.site_url').'/appoint/'.$appoint_id.'.html';
		}
	}
	
}

?>