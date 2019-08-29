<?php
/*
 * 收藏
 *
 * @  Writers    Jaty
 * @  BuildTime  2014/12/25 16:43
 * 
 */
class CollectAction extends BaseAction {
	protected function  _initialize(){
		parent::_initialize();
    	//导航条
    	$web_index_slider = D('Slider')->get_slider_by_key('web_slider');
    	$this->assign('web_index_slider',$web_index_slider);
    	
		//热门搜索词
    	$search_hot_list = D('Search_hot')->get_list(12);
    	$this->assign('search_hot_list',$search_hot_list);

		//所有分类 包含2级分类
		$all_category_list = D('Group_category')->get_category();
		$this->assign('all_category_list',$all_category_list);
	}
	
    public function index(){
		
		$this->assign(D('Group')->get_group_collect_list($this->user_session['uid']));
		
		$this->display();
    }
    
    public function meal()
    {
		$this->assign(D('Merchant_store')->get_meal_collect_list($this->user_session['uid']));
		$this->display();
    }

	public function merchant()
    {
		$this->assign(D('Merchant')->get_collect_list($this->user_session['uid']));
		$this->assign('uid',$this->user_session['uid']);
		$this->display();
    }
	public function group_order_view(){
		
		$now_order = D('Group_order')->get_order_detail_by_id($this->now_user['uid'],$_GET['order_id']);
		if(empty($now_order)){
			$this->error('当前订单不存在！');
		}else{
			$this->assign('now_order',$now_order);
		}
		
		$this->display();
	}
	
	public function meal_list()
	{
		$status = isset($_GET['status']) ? intval($_GET['status']) : -1;
		$where = array('uid' => $this->now_user['uid'], 'status' => array('lt', 2));
		if ($status == 0) {
			$where['paid'] = 0;
		} elseif ($status == 1) {
			$where['status'] = 0;
		} elseif ($status == 2) {
			$where['status'] = 1;
		}
		$orders = M("Meal_order")->where($where)->order('order_id DESC')->select();
		$tmp = array();
		foreach ($orders as $o) {
			$tmp[] = $o['store_id'];
		}
		if ($tmp) {
			$store_image_class = new store_image();
			$store = D('Merchant_store')->where(array('store_id' => array('in', $tmp)))->select();
			$list = array();
			foreach ($store as $v) {
				$images = $store_image_class->get_allImage_by_path($v['pic_info']);
				$v['image'] = $images ? array_shift($images) : array();
				$list[$v['store_id']] = $v;
			}
		}
		
		foreach ($orders as &$or) {
			$or['image'] = isset($list[$or['store_id']]['image']) ? $list[$or['store_id']]['image'] : '';
			$or['s_name'] = isset($list[$or['store_id']]['name']) ? $list[$or['store_id']]['name'] : '';
			$or['url'] = C('config.site_url').'/meal/'.$or['store_id'].'.html';
		}
		$this->assign('order_list', $orders);
		$this->assign('status', $status);

		$this->display();
	}
	
	public function meal_order_view()
	{
		$now_order = D('Meal_order')->get_order_by_id($this->now_user['uid'],$_GET['order_id']);
		$now_order['info'] = unserialize($now_order['info']);

		if ($now_order['pay_type']) {
			$now_order['pay_type_txt'] = D('Pay')->get_pay_name($now_order['pay_type'], 0);
		}
		if ($now_order['meal_pass']) {
			$now_order['meal_pass_txt'] = preg_replace('#(\d{4})#','$1 ',$now_order['meal_pass']);
		}
		if (empty($now_order)) {
			$this->error('当前订单不存在！');
		} else {
			$this->assign('now_order',$now_order);
		}
		
		$this->display();
	}
	
	public function meal_order_del()
	{
		$now_order = D('Meal_order')->get_order_by_id($this->now_user['uid'],$_GET['order_id']);
		if(empty($now_order)){
			$this->error('当前订单不存在！');
		}else{
			D('Meal_order')->where(array('uid' => $this->now_user['uid'], 'order_id' => $_GET['order_id']))->save(array('status' => 2));
			$this->success('订单删除成功', U('User/Index/meal_list'));
		}
	}
}