<?php
/**
 * 预约管理
 * AppointAction
 * @author yaolei
 */
class AppointAction extends BaseAction{
	/* 此方法下的首页信息 */
	public function index(){
		$database_apponit_category = D('Appoint_category');
		$condition_apponit_category['cat_fid'] = intval($_GET['cat_fid']);
		
		$count_apponit_category = $database_apponit_category->where($condition_apponit_category)->count();
		import('@.ORG.system_page');
		$page = new Page($count_apponit_category, 50);
		$category_list = $database_apponit_category->field(true)->where($condition_apponit_category)->order('`cat_sort` DESC, `cat_id` ASC')->limit($page->firstRow. ',' .$page->listRows)->select();
		$this->assign('category_list', $category_list);
		$pagebar = $page->show();
		$this->assign('pagebar', $pagebar);
		if($_GET['cat_fid']){
			$condition_now_apponit_category['cat_id'] = intval($_GET['cat_fid']);
			$now_category = $database_apponit_category->field(true)->where($condition_now_apponit_category)->find();
			if(empty($now_category)){
				$this->error_tips('没有找到该分类信息！', 3, U('Appoint/index'));
			}
			$this->assign('now_category', $now_category);
		}
		$this->display();
	}
	
	// 预约自定义表单所有字段展示
	public function cue_field(){
		$database_appoint_category = D('Appoint_category');
		$condition_now_appoint_category['cat_id'] = intval($_GET['cat_id']);
		$now_category = $database_appoint_category->field(true)->where($condition_now_appoint_category)->find();

		if(empty($now_category)){
			$this->frame_error_tips('没有找到该分类信息！');
		}
		/*if(!empty($now_category['cat_fid'])){
			$this->frame_error_tips('该分类不是主分类，无法使用商品字段功能！');
		}*/
		if(!empty($now_category['cue_field'])){
			$now_category['cue_field'] = unserialize($now_category['cue_field']);
			foreach ($now_category['cue_field'] as $val){
				$sort[] = $val['sort'];
			}
			array_multisort($sort, SORT_DESC, $now_category['cue_field']);
		}
		$this->assign('now_category',$now_category);
		$this->display();
	}
	
	// 预约自定义表单添加字段
	public function cue_field_add(){
		$this->assign('bg_color','#F3F3F3');
		
		$this->display();
	}
	
	// 预约自定义表单添加字段 操作
	public function cue_field_modify(){
		if(IS_POST){
			$database_appoint_category = D('Appoint_category');
			$condition_now_appoint_category['cat_id'] = intval($_POST['cat_id']);
			$now_category = $database_appoint_category->field(true)->where($condition_now_appoint_category)->find();
			
			if(!empty($now_category['cue_field'])){
				$cue_field = unserialize($now_category['cue_field']);
				foreach($cue_field as $key=>$value){
					if($value['name'] == $_POST['name']){
						$this->error('该填写项已经添加，请勿重复添加！');
					}
				}
			}else{
				$cue_field = array();
			}

			$post_data['name'] = $_POST['name'];
			$post_data['type'] = $_POST['type'];
			$post_data['sort'] = strval($_POST['sort']);
			$post_data['iswrite'] = $_POST['iswrite'];
			if(!empty($_POST['use_field'])){
				$post_data['use_field'] = explode(PHP_EOL, $_POST['use_field']);
			}
			
			// 多选框
//			if($_POST['type'] === '3'){
//				if(!empty($now_category['cat_field'])){
//					$cat_field = unserialize($now_category['cat_field']);
//					foreach($cat_field as $key=>$value){
//						if( (!empty($_POST['use_field']) && $value['use_field'] == $_POST['use_field'] && $_POST['name'] == $value['name']) ){
//							$this->error('字段已经添加，请勿重复添加！');
//						}
//					}
//				}else{
//					$cat_field = array();
//				}
//				if(empty($_POST['use_field'])){
//					$post_data_cat_field['name'] = $_POST['name'];
//					$post_data_cat_field['value'] = explode(PHP_EOL,$_POST['value']);
//					$post_data_cat_field['type'] = $_POST['type'];
//				}else{
//					$post_data_cat_field['use_field'] = $_POST['use_field'];
//				}
//				array_push($cat_field,$post_data_cat_field);
//				$data_group_category['cat_field'] = serialize($cat_field);
//				
//			}
			array_push($cue_field,$post_data);
			$data_group_category['cue_field'] = serialize($cue_field);
			$data_group_category['cat_id'] = $now_category['cat_id'];
			if($database_appoint_category->data($data_group_category)->save()){
				$this->success('添加成功！');
			}else{
				$this->error('添加失败！请重试~');
			}
		}else{
			$this->error('非法提交,请重新提交~');
		}
	}
	
	public function cue_field_del(){
		if(IS_POST){
			$database_group_category = D('Appoint_category');
			$condition_now_group_category['cat_id'] = intval($_POST['cat_id']);
			$now_category = $database_group_category->field(true)->where($condition_now_group_category)->find();
			
			if(!empty($now_category['cue_field'])){
				$cue_field = unserialize($now_category['cue_field']);
				$new_cue_field = array();
				$new_cat_field = array();
			
				foreach($cue_field as $key=>$value){
					if($value['name'] != $_POST['name']){
						array_push($new_cue_field,$value);
					}
				}
			}else{
				$this->error('此填写项不存在！');
			}
			$data_group_category['cue_field'] = serialize($new_cue_field);
			$data_group_category['cat_id'] = $now_category['cat_id'];
			if($database_group_category->data($data_group_category)->save()){
				$this->success('删除成功！');
			}else{
				$this->error('删除失败！请重试~');
			}
		}else{
			$this->error('非法提交,请重新提交~');
		}
	}
	
	/* 添加 */
	public function cat_add(){
		$this->assign('bg_color','#F3F3F3');
		$this->display();
	}
	/* 执行添加 */
	public function cat_modify(){
		if(IS_POST){
			$database_appoint_category = D('Appoint_category');
			$_POST['create_time'] = time();
			$condition_cat['cat_url'] = $_POST['cat_url'];
			$cat_url = $database_appoint_category->field('`cat_url`')->where($condition_cat)->find();
			if(!empty($cat_url)){
				$this->error('短标记已存在！');
			} else {
				if( $database_appoint_category->data($_POST)->add() ){
					$this->success('添加成功！');
				} else {
					$this->error('添加失败！请重试~');
				}
			}
		} else {
			$this->error('非法提交,请重新提交~');
		}
	}
	
	/* 编辑  */
	public function cat_edit(){
		$this->assign('bg_color','#F3F3F3');
		
		$database_appoint_category = D('Appoint_category');
		$database_now_appoint_category['cat_id'] = intval($_GET['cat_id']);
		$now_category = $database_appoint_category->field(true)->where($database_now_appoint_category)->find();
		if(empty($now_category)){
			$this->frame_error_tips('没有找到该分类信息！');
		}
		$this->assign('now_category', $now_category);
		$this->display();
	}
	/* 执行编辑 */
	public function cat_amend(){
		if(IS_POST){
			$database_group_category = D('Appoint_category');
			$condition_where['cat_id'] = $_POST['cat_id'];
			$cat_info = $database_group_category->field(true)->where($condition_where)->find();
			if($cat_info['cat_url'] != $_POST['cat_url']){
				if($database_group_category->field(true)->where(array('cat_url'=>$_POST['cat_url']))->find()){
					$this->frame_submit_tips(0, '短标记已存在！');
				}
			}
			if($cat_info['cat_name'] != $_POST['cat_name']){
				if($database_group_category->field(true)->where(array('cat_name'=>$_POST['cat_name']))->find()){
					$this->frame_submit_tips(0, '分类名称已存在！');
				}
			}
			if($database_group_category->data($_POST)->save()){
				$this->frame_submit_tips(1, '编辑成功！');
			}else{
				$this->frame_submit_tips(0, '编辑失败！请重试~');
			}
		}else{
			$this->frame_submit_tips(0, '非法提交,请重新提交~');
		}
	}
	
	/* 删除 */
	public function cat_del(){
		if(IS_POST){
			$database_appoint_category = D('Appoint_category');
			$condition_now_appoint_category['cat_id'] = intval($_POST['cat_id']);
			$now_category = $database_appoint_category->field(true)->where($condition_now_appoint_category)->find();
			if($database_appoint_category->where($condition_now_appoint_category)->delete()){
				if(empty($now_category['cat_fid'])){
					$condition_son_appoint_category['cat_fid'] = $now_category['cat_id'];
					$database_appoint_category->where($condition_son_appoint_category)->delete();
					$condition_appoint['cat_fid'] = $now_category['cat_id'];
				} else {
					$condition_appoint['cat_id'] = $now_category['cat_id'];
				}
				D('Appoint')->where($condition_appoint)->delete();
				$this->success('删除成功！');
			} else {
				$this->error('删除失败！请重试~');
			}
		} else {
			$this->error('非法提交,请重新提交~');
		}
	}
	
	/* 服务列表 */
	public function product_list(){
		//筛选
		if(!empty($_GET['keyword'])){
			if($_GET['searchtype'] == 'appoint_id'){
				$condition_where['appoint_id'] = intval($_GET['keyword']);
			} elseif($_GET['searchtype'] == 'appoint_name') {
				$condition_where['appoint_name'] = array('LIKE', '%'.$_GET['keyword'].'%');
			}
		}
		$database_appoint = D('Appoint');
		$database_merchant = D('Merchant');
		$database_category = D('Appoint_category');
		$appoint_count = $database_appoint->count();
		
		import('@.ORG.system_page');
		$page = new Page($appoint_count, 20);
		$appoint_info = $database_appoint->field(true)->where($condition_where)->order('`sort`, `appoint_id` DESC')->limit($page->firstRow. ',' .$page->listRows)->select();
		$merchant_info = $database_merchant->field(true)->select();
		$category_info = $database_category->field(true)->select();
		$appoint_list = $this->formatArray($appoint_info, $merchant_info, $category_info);
		foreach($appoint_list as $key=>$val){
			if($appoint_list[$key]['category_name'] == null){
				$cat_where['cat_id'] = $val['cat_fid'];
				$category_name = $database_category->field('`cat_name`')->where($cat_where)->find();
				$appoint_list[$key]['category_name'] = implode('',$category_name);
			}
		}
		
		$this->assign('appoint_list', $appoint_list);
		$pagebar = $page->show();
		$this->assign('pagebar', $pagebar);
		$this->display();
	}
	
	/* 服务详情 */
	public function product_detail(){
		$this->assign('bg_color','#F3F3F3');
		
		$database_appoint_product = D('Appoint');
		$database_merchant = D('Merchant');
		$database_category = D('Appoint_category');
		$where['appoint_id'] = intval($_GET['appoint_id']);
		$appoint = $database_appoint_product->field(true)->where($where)->find();
		if(empty($appoint)){
			$this->frame_error_tips('没有找到该服务的信息！');
		}
		$where_mer['mer_id'] = $appoint['mer_id'];
		$mer_info = $database_merchant->field(true)->where($where_mer)->find();
		$appoint['mer_name'] = $mer_info['name'];
		if($appoint['cat_id'] != 0){
			$where_category['cat_id'] = $appoint['cat_id'];
		} else {
			$where_category['cat_id'] = $appoint['cat_fid'];
		}
		$category_info = $database_category->field(true)->where($where_category)->find();
		
		$appoint['cat_name'] = $category_info['cat_name'];
		$this->assign('appoint', $appoint);
		$this->display();
	}
	
	/*订单列表*/
	public function order_list(){
		$database_order = D('Appoint_order');
    	$database_user = D('User');
    	$database_appoint = D('Appoint');
    	$database_store = D('Merchant_store');
    	$where['appoint_id'] = intval($_GET['appoint_id']);
		$now_appoint = $database_appoint->field(true)->where($where)->find();
    	$order_count = $database_order->where($where)->count();
    	
    	import('@.ORG.system_page');
    	$page = new Page($order_count, 20);
    	$order_info = $database_order->field(true)->where($where)->order('`order_id` DESC')->limit($page->firstRow.','.$page->listRows)->select();
		if(empty($order_info)){
			$this->error_tips('当前预约未产生订单！');
		}
    	$user_info = $database_user->field('`uid`, `phone`, `nickname`')->select();
    	$appoint_info = $database_appoint->field('`appoint_id`, `appoint_name`, `appoint_type`, `appoint_price`')->select();
    	$store_info = $database_store->field('`store_id`, `name`, `adress`')->select();
    	$order_list = $this->formatOrderArray($order_info, $user_info, $appoint_info, $store_info);
    	
    	//商家信息
		$database_merchant = D('Merchant');
		$condition_merchant['mer_id'] = $now_appoint['mer_id'];
		$now_merchant = $database_merchant->field(true)->where($condition_merchant)->find();
		if(empty($now_merchant)){
			$this->error_tips('当前'.$this->config['group_alias_name'].'所属的商家不存在！');
		}
		$this->assign('now_merchant', $now_merchant);
    	
    	$pagebar = $page->show();
    	$this->assign('now_appoint', $now_appoint);
    	$this->assign('pagebar', $pagebar);
    	$this->assign('order_list', $order_list);
    	$this->display();
	}
	
	/* 订单详情  */
    public function order_detail(){
		$this->assign('bg_color','#F3F3F3');
		
    	$database_order = D('Appoint_order');
    	$database_user = D('User');
    	$database_appoint = D('Appoint');
    	$database_store = D('Merchant_store');
    	$where['order_id'] = intval($_GET['order_id']);
    	
    	$now_order = $database_order->field(true)->where($where)->find();
    	$where_user['uid'] = $now_order['uid'];
    	$user_info = $database_user->field('`uid`, `phone`, `nickname`')->where($where_user)->find();
    	$where_appoint['appoint_id'] = $now_order['appoint_id'];
    	$appoint_info = $database_appoint->field('`appoint_id`, `appoint_name`, `appoint_type`, `appoint_price`')->where($where_appoint)->find();
    	$where_store['store_id'] = $now_order['store_id'];
    	$store_info = $database_store->field('`store_id`, `name`, `adress`')->where($where_store)->find();
    	
    	$now_order['phone'] = $user_info['phone'];
    	$now_order['nickname'] = $user_info['nickname'];
    	$now_order['appoint_name'] = $appoint_info['appoint_name'];
    	$now_order['appoint_type'] = $appoint_info['appoint_type'];
    	$now_order['appoint_price'] = $appoint_info['appoint_price'];
    	$now_order['store_name'] = $store_info['name'];
    	$now_order['store_adress'] = $store_info['adress'];
    	$cue_info = unserialize($now_order['cue_field']);
    	$cue_list = array();
    	foreach($cue_info as $key=>$val){
    		if(!empty($cue_info[$key]['value'])){
    			$cue_list[$key]['name'] = $val['name'];
    			$cue_list[$key]['value'] = $val['value'];
    			$cue_list[$key]['type'] = $val['type'];
    			if($cue_info[$key]['type'] == 2){
    				$cue_list[$key]['long'] = $val['long'];
    				$cue_list[$key]['lat'] = $val['lat'];
    				$cue_list[$key]['address'] = $val['address'];
    			}
    		}
    	}
    	
    	$this->assign('cue_list', $cue_list);
    	$this->assign('now_order', $now_order);
    	$this->display();
    }
	
	/* 格式化服务列表数据 */
	public function formatArray($appoint_info, $merchant_info, $category_info){
		if(!empty($merchant_info)){
			$merchant_array = array();
			foreach($merchant_info as $val ){
				$merchant_array[$val['mer_id']]['mer_name'] = $val['name'];
			}
		}
		if(!empty($category_info)){
			$category_array = array();
			foreach($category_info as $val){
				$category_array[$val['cat_id']]['category_name'] = $val['cat_name'];
			}
		}
		if(!empty($appoint_info)){
			foreach($appoint_info as &$val ){
				$val['mer_name'] = $merchant_array[$val['mer_id']]['mer_name'];
				$val['category_name'] = $category_array[$val['cat_id']]['category_name'];
			}
		}
		return $appoint_info;
	}
	
	/* 格式化订单数据  */
    protected function formatOrderArray($order_info, $user_info, $appoint_info, $store_info){
    	if(!empty($user_info)){
    		$user_array = array();
    		foreach($user_info as $val){
    			$user_array[$val['uid']]['phone'] = $val['phone'];
    			$user_array[$val['uid']]['nickname'] = $val['nickname'];
    		}
    	}
    	if(!empty($appoint_info)){
    		$appoint_array = array();
    		foreach($appoint_info as $val){
    			$appoint_array[$val['appoint_id']]['appoint_name'] = $val['appoint_name'];
    			$appoint_array[$val['appoint_id']]['appoint_type'] = $val['appoint_type'];
    			$appoint_array[$val['appoint_id']]['appoint_price'] = $val['appoint_price'];
    		}
    	}
    	if(!empty($store_info)){
    		$store_array = array();
    		foreach($store_info as $val){
    			$store_array[$val['store_id']]['store_name'] = $val['name'];
    			$store_array[$val['store_id']]['store_adress'] = $val['adress'];
    		}
    	}
    	if(!empty($order_info)){
    		foreach($order_info as &$val){
    			$val['phone'] = $user_array[$val['uid']]['phone'];
    			$val['nickname'] = $user_array[$val['uid']]['nickname'];
    			$val['appoint_name'] = $appoint_array[$val['appoint_id']]['appoint_name'];
    			$val['appoint_type'] = $appoint_array[$val['appoint_id']]['appoint_type'];
    			$val['appoint_price'] = $appoint_array[$val['appoint_id']]['appoint_price'];
    			$val['store_name'] = $store_array[$val['store_id']]['store_name'];
    			$val['store_adress'] = $store_array[$val['store_id']]['store_adress'];
    		}
    	}
    	return $order_info;
    }
}