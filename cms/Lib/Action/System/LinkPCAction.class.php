<?php
class LinkPCAction extends BaseAction
{
	public $modules;
	
	public function _initialize() 
	{
		parent::_initialize();
		
		$this->modules = array(
			'Home' => '首页', 
			'Activity' => '限时活动', 
			'KuaiDian' => '快店', 
			'Appoint' => '上门服务', 
			'Meal' => '餐饮', 
			'Group' => '团购', 
			'Group_Xiuxianyule' => '休息娱乐', 
			'Group_Around' => '附近优惠', 
			'Classify' => '分类信息', 
			'Life' => '生活缴费'
		);
	}
	public function insert()
	{
		$modules = $this->modules();
		$this->assign('modules', $modules);
		$this->display();
	}
	public function modules()
	{
		
		$t = array();
		$t[] = array('module' => 'Home', 'linkcode' => $this->config['site_url'], 'name' => '首页', 'sub' => 0, 'canselected' => 1,'linkurl' => '','keyword' => $this->modules['Home'], 'askeyword' => 1);
		$t[] = array('module' => 'Activity', 'linkcode' => $this->config['site_url'] . '/activity/', 'name' => '限时活动', 'sub' => 1, 'canselected' => 1,'linkurl' => '','keyword' => $this->modules['Activity'], 'askeyword' => 1);
		$t[] = array('module' => 'KuaiDian', 'linkcode' => $this->config['site_url'] . '/kd/all', 'name' => '快店', 'sub' => 1, 'canselected' => 1,'linkurl' => '','keyword' => $this->modules['KuaiDian'], 'askeyword' => 1);
		$t[] = array('module' => 'Appoint', 'linkcode' => $this->config['site_url'] . '/appoint/', 'name' => '预约', 'sub' => 1, 'canselected' => 1,'linkurl' => '','keyword' => $this->modules['Appoint'], 'askeyword' => 1);
		$t[] = array('module' => 'Meal', 'linkcode' => $this->config['site_url'] . '/meal/all', 'name' => '餐饮', 'sub' => 1, 'canselected' => 1,'linkurl' => '','keyword' => $this->modules['Meal'], 'askeyword' => 1);
		$t[] = array('module' => 'Group', 'linkcode' => $this->config['site_url'] . '/category/all/all', 'name' => '团购', 'sub' => 1, 'canselected' => 1,'linkurl' => '','keyword' => $this->modules['Group'], 'askeyword' => 1);
		$t[] = array('module' => 'Group_Around', 'linkcode' => $this->config['site_url'] . '/group/around/', 'name' => '附近优惠', 'sub' => 0, 'canselected' => 1,'linkurl' => '','keyword' => $this->modules['Group_Around'], 'askeyword' => 1);
		$t[] = array('module' => 'Classify', 'linkcode' => $this->config['site_url'] . '/classify/', 'name' => '分类信息', 'sub' => 1, 'canselected' => 1,'linkurl' => '','keyword' => $this->modules['Classify'], 'askeyword' => 1);
		$t[] = array('module' => 'Life', 'linkcode' => $this->config['site_url'] . '/topic/life.html', 'name' => '生活缴费', 'sub' => 0, 'canselected' => 1,'linkurl' => '','keyword' => $this->modules['Life'], 'askeyword' => 1);

		return $t;
	}
	
	
	public function Activity()
	{
		$this->assign('moduleName', $this->modules['Activity']);
		$where = array('status' => 1, 'is_finish' => 0);
		$db = D('Extension_activity_list');
		$count      = $db->where($where)->count();
		$Page       = new Page($count, 5);
		$show       = $Page->show();
		
		$list = $db->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
		$items = array();
		foreach ($list as $item){
			array_push($items, array('id' => $item['pigcms_id'], 'sub' => 0, 'name' => $item['title'], 'linkcode'=> $this->config['site_url'] . '/activity/' . $item['pigcms_id'] . '.html','sublink' => '','keyword' => $item['name']));
		}
		$this->assign('list', $items);
		$this->assign('page', $show);
		$this->display('detail');
	}
	
	public function Group()
	{
		$this->assign('moduleName', $this->modules['Group']);
		$cat_fid = isset($_GET['cat_fid']) ? intval($_GET['cat_fid']) : 0;
		$where = array('cat_fid' => $cat_fid);
		$db = D('Group_category');
		$count      = $db->where($where)->count();
		$Page       = new Page($count, 5);
		$show       = $Page->show();
		
		$list = $db->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
		$items = array();
		foreach ($list as $item){
			if ($db->where(array('cat_fid' => $item['cat_id']))->find()) {
				array_push($items, array('id' => $item['cat_id'], 'sub' => 1, 'name' => $item['cat_name'], 'linkcode'=> $this->config['site_url'] . '/category/' . $item['cat_url'] . '/all','sublink' => U('LinkPC/Group', array('cat_fid' => $item['cat_id']), true, false, true),'keyword' => $item['cat_name']));
			} else {
				array_push($items, array('id' => $item['cat_id'], 'sub' => 0, 'name' => $item['cat_name'], 'linkcode'=> $this->config['site_url'] . '/category/' . $item['cat_url'] . '/all','sublink' => '','keyword' => $item['cat_name']));
			}
		}
		$this->assign('list', $items);
		$this->assign('page', $show);
		$this->display('detail');
	}
	
	public function KuaiDian()
	{
		$this->assign('moduleName', $this->modules['KuaiDian']);
		$cat_fid = isset($_GET['cat_fid']) ? intval($_GET['cat_fid']) : 0;
		$where = array('cat_fid' => $cat_fid, 'status' => 1);
		$db = D('Meal_store_category');
		$count      = $db->where($where)->count();
		$Page       = new Page($count, 5);
		$show       = $Page->show();
		
		$list = $db->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
		$items = array();
		foreach ($list as $item){
			if ($db->where(array('cat_fid' => $item['cat_id']))->find()) {
				array_push($items, array('id' => $item['cat_id'], 'sub' => 1, 'name' => $item['cat_name'], 'linkcode'=> $this->config['site_url'] . '/kd/' . $item['cat_url'] . '/all','sublink' => U('LinkPC/KuaiDian', array('cat_fid' => $item['cat_id']), true, false, true),'keyword' => $item['cat_name']));
			} else {
				array_push($items, array('id' => $item['cat_id'], 'sub' => 0, 'name' => $item['cat_name'], 'linkcode'=> $this->config['site_url'] . '/kd/' . $item['cat_url'] . '/all','sublink' => '','keyword' => $item['cat_name']));
			}
		}
		$this->assign('list', $items);
		$this->assign('page', $show);
		$this->display('detail');
	}
	
	public function Meal()
	{
		$this->assign('moduleName', $this->modules['Meal']);
		$cat_fid = isset($_GET['cat_fid']) ? intval($_GET['cat_fid']) : 0;
		$where = array('cat_fid' => $cat_fid, 'status' => 1);
		$db = D('Meal_store_category');
		$count      = $db->where($where)->count();
		$Page       = new Page($count, 5);
		$show       = $Page->show();
		
		$list = $db->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
		$items = array();
		foreach ($list as $item){
			if ($db->where(array('cat_fid' => $item['cat_id']))->find()) {
				array_push($items, array('id' => $item['cat_id'], 'sub' => 1, 'name' => $item['cat_name'], 'linkcode'=> $this->config['site_url'] . '/kd/' . $item['cat_url'] . '/all','sublink' => U('LinkPC/KuaiDian', array('cat_fid' => $item['cat_id']), true, false, true),'keyword' => $item['cat_name']));
			} else {
				array_push($items, array('id' => $item['cat_id'], 'sub' => 0, 'name' => $item['cat_name'], 'linkcode'=> $this->config['site_url'] . '/kd/' . $item['cat_url'] . '/all','sublink' => '','keyword' => $item['cat_name']));
			}
		}
		$this->assign('list', $items);
		$this->assign('page', $show);
		$this->display('detail');
	}
	
	public function Classify()
	{
		$this->assign('moduleName', $this->modules['Classify']);
		$where = array('subdir' => 1, 'cat_status' => 1);
		$db = D('Classify_category');
		$count      = $db->where($where)->count();
		$Page       = new Page($count, 5);
		$show       = $Page->show();
		
		$list = $db->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
		$items = array();
		foreach ($list as $item){
			array_push($items, array('id' => $item['cid'], 'sub' => 0, 'name' => $item['cat_name'], 'linkcode'=> $this->config['site_url'] . '/classify/subdirectory-' . $item['cid'] . 'html','sublink' => '','keyword' => $item['cat_name']));
		}
		$this->assign('list', $items);
		$this->assign('page', $show);
		$this->display('detail');
	}
	
	public function Appoint()
	{
		$this->assign('moduleName', $this->modules['Appoint']);
		$cat_fid = isset($_GET['cat_fid']) ? intval($_GET['cat_fid']) : 0;
		$where = array('cat_fid' => $cat_fid, 'cat_status' => 1);
		$db = D('Appoint_category');
		$count      = $db->where($where)->count();
		$Page       = new Page($count, 5);
		$show       = $Page->show();
		
		$list = $db->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
		$items = array();
		foreach ($list as $item){
			if ($db->where(array('cat_fid' => $item['cat_id']))->find()) {
				array_push($items, array('id' => $item['cat_id'], 'sub' => 1, 'name' => $item['cat_name'], 'linkcode'=> $this->config['site_url'] . '/appoint/category/' . $item['cat_url'] . '/all','sublink' => U('LinkPC/Appoint', array('cat_fid' => $item['cat_id']), true, false, true),'keyword' => $item['cat_name']));
			} else {
				array_push($items, array('id' => $item['cat_id'], 'sub' => 0, 'name' => $item['cat_name'], 'linkcode'=> $this->config['site_url'] . '/appoint/category/' . $item['cat_url'] . '/all','sublink' => '','keyword' => $item['cat_name']));
			}
		}
		$this->assign('list', $items);
		$this->assign('page', $show);
		$this->display('detail');
	}
	
}
?>