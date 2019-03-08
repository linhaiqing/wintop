<?php

/*
 * 用户中心
 *
 * @  Writers    yanleilei
 * @  BuildTime  2015/8/18 18:25
 * 
 */

class DeliverAction extends BaseAction {
	protected $deliver_user, $deliver_store, $deliver_location, $deliver_supply;
	
	protected function _initialize() {
		parent::_initialize();
		$this->deliver_user = D("Deliver_user");
		$this->deliver_store = D("Deliver_store");
		$this->deliver_location = D("Deliver_location");
		$this->deliver_supply = D("Deliver_supply");
	}
	/**
	 * 配送员列表
	 */
    public function user() {
        //搜索
        if (!empty($_GET['keyword'])) {
            if ($_GET['searchtype'] == 'uid') {
                $condition_user['uid'] = $_GET['keyword'];
            } else if ($_GET['searchtype'] == 'nickname') {
                $condition_user['name'] = array('like', '%' . $_GET['keyword'] . '%');
            } else if ($_GET['searchtype'] == 'phone') {
                $condition_user['phone'] = array('like', '%' . $_GET['keyword'] . '%');
            }
        }
        $condition_user['group'] = 1;
        $count_user = $this->deliver_user->where($condition_user)->count();
        import('@.ORG.system_page');
        $p = new Page($count_user, 15);
        $user_list = $this->deliver_user->field(true)->where($condition_user)->order('`uid` DESC')->limit($p->firstRow . ',' . $p->listRows)->select();
        
        $this->assign('user_list', $user_list);
        $pagebar = $p->show();
        $this->assign('pagebar', $pagebar);
        $this->display();
    }
    
    /**
     * 配送员添加
     */
    public function user_add() {
    	if($_POST){
    		$column['name'] = $_POST['name'];
    		$column['phone'] = $_POST['phone'];
    		$column['pwd'] = md5($_POST['pwd']);
    		$column['store_id'] = 0;
    		$column['city_id'] = $_POST['city_id'];
    		$column['province_id'] = $_POST['province_id'];
    		$column['circle_id'] = $_POST['circle_id'];
    		$column['area_id'] = $_POST['area_id'];
    		$column['site'] = $_POST['adress'];
    		$long_lat = explode(',',$_POST['long_lat']);
    		$column['lng'] = $long_lat[0];
    		$column['lat'] = $long_lat[1];
    		$column['create_time'] = $_SERVER['REQUEST_TIME'];
    		$column['status'] = intval($_POST['status']);
    		$column['last_time'] = $_SERVER['REQUEST_TIME'];
    		$column['group'] = 1;
    		$column['range'] = intval($_POST['range']);
    		
    		$id = D('deliver_user')->data($column)->add();
    		if(!$id){
    			$this->error('保存失败，请重试');
    		}
    		$this->success('保存成功');
    	}
    	
    	$this->display();
    }
    
    /**
     * 配送员修改
     */
    public function user_edit() {
    	if($_POST){
    		$uid = intval($_POST['uid']);
    		$column['name'] = $_POST['name'];
    		$column['phone'] = $_POST['phone'];
    		if($_POST['pwd']){
    			$column['pwd'] = md5($_POST['pwd']);
    		}
    		$column['city_id'] = $_POST['city_id'];
    		$column['province_id'] = $_POST['province_id'];
    		$column['circle_id'] = $_POST['circle_id'];
    		$column['area_id'] = $_POST['area_id'];
    		$column['site'] = $_POST['adress'];
    		$long_lat = explode(',',$_POST['long_lat']);
    		$column['lng'] = $long_lat[0];
    		$column['lat'] = $long_lat[1];
    		$column['status'] = intval($_POST['status']);
    		$column['last_time'] = $_SERVER['REQUEST_TIME'];
    		$column['range'] = intval($_POST['range']);
    		if(D('deliver_user')->where(array('uid'=>$uid))->data($column)->save()){
    			$this->success('修改成功！');
    		}else{
    			$this->error('修改失败！请检查内容是否有过修改（必须修改）后重试~');
    		}
    	}else{
    		$uid = $_GET['uid'];
    		if(!$uid){
    			$this->error('非法操作');
    		}
    		$deliver = D('deliver_user')->where(array('uid'=>$uid))->find();
    		if(!$deliver){
    			$this->error('非法操作');
    		}
    		$this->assign('now_user',$deliver);
    	}
    	$this->display();
    }
}