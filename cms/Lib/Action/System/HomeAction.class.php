<?php
/*
 * 首页回复管理
 *
 * @  Writers    Jaty
 * @  BuildTime  2014/11/06 16:47
 * 
 */
class HomeAction extends BaseAction
{
	public function index()
	{
		$paltform = D("Platform")->where(array('key' => '首页'))->find();
		          
		if (IS_POST) {
			$data = array();
			$data['title'] = isset($_POST['title']) ? htmlspecialchars($_POST['title']) : '';
			$data['info'] = isset($_POST['info']) ? htmlspecialchars($_POST['info']) : '';
			$data['key'] = '首页';
			$data['url'] = $this->config['site_url'] . '/wap.php';
// 			$images = $this->upload();
// 			if (empty($images['error'])) {
// 				foreach ($images['msg'] as $image) {
// 					$data[$image['key']] = substr($image['savepath'] . $image['savename'], 1);
// 				}
// 			}

			$image = D('Image')->handle($this->system_session['id'], 'platform');
			if (!$image['error']) {
				$data = array_merge($data, $image['url']);
			}
			if ($paltform == false) {
				$id = D("Platform")->add($data);
				D('Image')->update_table_id($_POST['pic'], $id, 'platform');
			} else {
				D("Platform")->where(array('id' => $paltform['id']))->save($data);
				D('Image')->update_table_id($_POST['pic'], $paltform['id'], 'platform');
			}
			$this->success("设置成功");
		} else {
			if (isset($paltform['pic']) && $paltform['pic']) $paltform['pic'] = $this->config['site_url'] . $paltform['pic'];
			$this->assign('info',$paltform);
			$this->display();
		}
	}
	
	public function group()
	{
		$paltform = D("Platform")->where(array('key' => $this->config['group_alias_name']))->find();
		if (IS_POST) {
			$data = array();
			$data['title'] = isset($_POST['title']) ? htmlspecialchars($_POST['title']) : '';
			$data['info'] = isset($_POST['info']) ? htmlspecialchars($_POST['info']) : '';
			$data['key'] = $this->config['group_alias_name'];
			$data['url'] = $this->config['site_url'] . '/wap.php?g=Wap&c=Group&a=index';
// 			$images = $this->upload();
// 			if (empty($images['error'])) {
// 				foreach ($images['msg'] as $image) {
// 					$data[$image['key']] = substr($image['savepath'] . $image['savename'], 1);
// 				}
// 			}

			$image = D('Image')->handle($this->system_session['id'], 'platform');
			if (!$image['error']) {
				$data = array_merge($data, $image['url']);
			}
			if ($paltform == false) {
				D("Platform")->add($data);
				D('Image')->update_table_id($_POST['pic'], $id, 'platform');
			} else {
				D("Platform")->where(array('id' => $paltform['id']))->save($data);	
				D('Image')->update_table_id($_POST['pic'], $paltform['id'], 'platform');
			}
			$this->success("设置成功");
		} else {
			if (isset($paltform['pic']) && $paltform['pic']) $paltform['pic'] = $this->config['site_url'] . $paltform['pic'];
			$this->assign('info',$paltform);
			$this->display();
		}
	}
	
	public function meal()
	{
		$paltform = D("Platform")->where(array('key' => $this->config['meal_alias_name']))->find();
		if (IS_POST) {
			$data = array();
			$data['title'] = isset($_POST['title']) ? htmlspecialchars($_POST['title']) : '';
			$data['info'] = isset($_POST['info']) ? htmlspecialchars($_POST['info']) : '';
			$data['key'] = $this->config['meal_alias_name'];
			$data['url'] = $this->config['site_url'] . '/wap.php?g=Wap&c=Meal_list&a=index';
// 			$images = $this->upload();
// 			if (empty($images['error'])) {
// 				foreach ($images['msg'] as $image) {
// 					$data[$image['key']] = substr($image['savepath'] . $image['savename'], 1);;
// 				}
// 			}

			$image = D('Image')->handle($this->system_session['id'], 'platform');
			if (!$image['error']) {
				$data = array_merge($data, $image['url']);
			}
			if ($paltform == false) {
				D("Platform")->add($data);
				D('Image')->update_table_id($_POST['pic'], $id, 'platform');
			} else {
				D("Platform")->where(array('id' => $paltform['id']))->save($data);	
				D('Image')->update_table_id($_POST['pic'], $paltform['id'], 'platform');	
			}
			$this->success("设置成功");
		} else {
			if (isset($paltform['pic']) && $paltform['pic']) $paltform['pic'] = $this->config['site_url'] . $paltform['pic'];
			$this->assign('info',$paltform);
			$this->display();
		}
	}
	
	public function first()
	{
		$first = D("First")->field(true)->where(array('reply_type' => 0))->find();
		if (IS_POST) {
			$data = array();
			$data['title'] = isset($_POST['title']) ? htmlspecialchars($_POST['title']) : '';
			$data['info'] = isset($_POST['info']) ? htmlspecialchars($_POST['info']) : '';
			$data['content'] = isset($_POST['content']) ? htmlspecialchars_decode($_POST['content']) : '';
			$data['url'] = isset($_POST['url']) ? htmlspecialchars_decode($_POST['url']) : '';
			$data['type'] = isset($_POST['type']) ? intval($_POST['type']) : 0;
			$data['fromid'] = isset($_POST['fromid']) ? intval($_POST['fromid']) : 0;
			$data['reply_type'] = 0;
			
		
			
			$image = D('Image')->handle($this->system_session['id'], 'first');
			if (!$image['error']) {
				$data = array_merge($data, $image['url']);
			}
			
// 			$images = $this->upload();
// 			if (empty($images['error'])) {
// 				foreach ($images['msg'] as $image) {
// 					$data[$image['key']] = substr($image['savepath'] . $image['savename'], 1);
// 				}
// 			}
			if ($first == false) {
				$id = D("First")->add($data);

				D('Image')->update_table_id($_POST['pic'], $id, 'first');
			} else {
				D("First")->where(array('id' => $first['id']))->save($data);	

				D('Image')->update_table_id($_POST['pic'], $first['id'], 'first');
			}
			$this->success("设置成功");
		} else {
			if (isset($first['pic']) && $first['pic']) $first['pic'] = $this->config['site_url'] . $first['pic'];
			$this->assign('first',$first);
			$this->display();
		}
	}
	
	public function invalid()
	{
		$invalid = D("First")->field(true)->where(array('reply_type' => 1))->find();
		if (IS_POST) {
			$data = array();
			$data['title'] = isset($_POST['title']) ? htmlspecialchars($_POST['title']) : '';
			$data['info'] = isset($_POST['info']) ? htmlspecialchars($_POST['info']) : '';
			$data['content'] = isset($_POST['content']) ? htmlspecialchars($_POST['content']) : '';
			$data['url'] = isset($_POST['url']) ? htmlspecialchars_decode($_POST['url']) : '';
			$data['type'] = isset($_POST['type']) ? intval($_POST['type']) : 0;
			$data['fromid'] = isset($_POST['fromid']) ? intval($_POST['fromid']) : 0;
			$data['reply_type'] = 1;
// 			$images = $this->upload();
// 			if (empty($images['error'])) {
// 				foreach ($images['msg'] as $image) {
// 					$data[$image['key']] = substr($image['savepath'] . $image['savename'], 1);
// 				}
// 			}
			$image = D('Image')->handle($this->system_session['id'], 'first');
			if (!$image['error']) {
				$data = array_merge($data, $image['url']);
			}
			if ($invalid == false) {
				$id = D("First")->add($data);
				D('Image')->update_table_id($_POST['pic'], $id, 'first');
			} else {
				D("First")->where(array('id' => $invalid['id']))->save($data);
				D('Image')->update_table_id($_POST['pic'], $invalid['id'], 'first');
			}
			$this->success("设置成功");
		} else {
			if (isset($invalid['pic']) && $invalid['pic']) $invalid['pic'] = $this->config['site_url'] . $invalid['pic'];
			$this->assign('first', $invalid);
			$this->display();
		}
	}
	
	public function other()
	{
		$list = D("Platform")->field(true)->select();
		$this->assign('list', $list);
		$this->display();
	}
	
	public function other_add()
	{
		if (IS_POST) {
			$data = array();
			$data['key'] = isset($_POST['key']) ? htmlspecialchars($_POST['key']) : '';
			$data['title'] = isset($_POST['title']) ? htmlspecialchars($_POST['title']) : '';
			$data['info'] = isset($_POST['info']) ? htmlspecialchars($_POST['info']) : '';
			$data['content'] = isset($_POST['content']) ? $_POST['content'] : '';
			$data['url'] = isset($_POST['url']) ? htmlspecialchars_decode($_POST['url']) : '';
			$data['api_url'] = isset($_POST['api_url']) ? htmlspecialchars_decode($_POST['api_url']) : '';
			if (empty($data['key'])) {
				$this->error('关键词不能为空');
			}
// 			$platform = M('Platform')->where(array('key' => $data['key']))->find();
// 			if ($platform) {
// 				$this->error('关键词已存在，不能重复添加！');
// 			}
// 			$images = $this->upload();
// 			if (empty($images['error'])) {
// 				foreach ($images['msg'] as $image) {
// 					$data[$image['key']] = substr($image['savepath'] . $image['savename'], 1);
// 				}
// 			}
			$image = D('Image')->handle($this->system_session['id'], 'platform');
			if (!$image['error']) {
				$data = array_merge($data, $image['url']);
			}
			if ($id = D("Platform")->add($data)) {
				D('Image')->update_table_id($_POST['pic'], $id, 'platform');
				$this->success('添加成功');
			} else {
				$this->error('添加失败');
			}
		} else {
			$this->display();
		}
	}
	
	public function other_edit()
	{
		if (IS_POST) {
			$data = array();
			$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
			$data['key'] = isset($_POST['key']) ? htmlspecialchars($_POST['key']) : '';
			$data['title'] = isset($_POST['title']) ? htmlspecialchars($_POST['title']) : '';
			$data['info'] = isset($_POST['info']) ? htmlspecialchars($_POST['info']) : '';
			$data['content'] = isset($_POST['content']) ? $_POST['content'] : '';
			$data['url'] = isset($_POST['url']) ? htmlspecialchars_decode($_POST['url']) : '';
			$data['api_url'] = isset($_POST['api_url']) ? htmlspecialchars_decode($_POST['api_url']) : '';
			if (empty($data['key'])) {
				$this->error('关键词不能为空');
			}
// 			$platform = M('Platform')->where("`id`<>'$id' AND `key`='{$data['key']}'")->find();
// 			if ($platform) {
// 				$this->error('关键词已存在，不能重复添加！');
// 			}
// 			$images = $this->upload();
// 			if (empty($images['error'])) {
// 				foreach ($images['msg'] as $image) {
// 					$data[$image['key']] = substr($image['savepath'] . $image['savename'], 1);
// 				}
// 			}

			$image = D('Image')->handle($this->system_session['id'], 'platform');
			if (!$image['error']) {
				$data = array_merge($data, $image['url']);
			}
			
			D('Platform')->where(array('id' => $id))->save($data);
			D('Image')->update_table_id($_POST['pic'], $id, 'platform');
			$this->success('更新成功');
		} else {
			$data = M('Platform')->where(array('id' => $this->_get('id')))->find();
			if ($data==false) {
				$this->error('您所操作的数据对象不存在！');
			}
			$this->assign('info', $data);
			$this->display();
		}
		
	}
	
	public function other_del()
	{
		D('Platform')->where(array('id' => intval($_POST['id'])))->delete();
		$this->success('删除成功');
	}
	
	public function diytool()
	{
		$this->display();
	}
}