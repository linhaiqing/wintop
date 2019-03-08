<?php

/*
 * 社区O2O功能
 *
 */

class HouseAction extends BaseAction{
    public function village(){
        //搜索
        if (!empty($_GET['keyword'])) {
            if ($_GET['searchtype'] == 'uid') {
                $condition_house_village['uid'] = $_GET['keyword'];
            } else if ($_GET['searchtype'] == 'nickname') {
                $condition_house_village['nickname'] = array('like', '%' . $_GET['keyword'] . '%');
            } else if ($_GET['searchtype'] == 'phone') {
                $condition_house_village['phone'] = array('like', '%' . $_GET['keyword'] . '%');
            }
        }
        $database_house_village = D('House_village');
        $count_village = $database_house_village->where($condition_house_village)->count();
        import('@.ORG.system_page');
        $p = new Page($count_village, 15);
        $village_list = $database_house_village->field(true)->where($condition_house_village)->order('`village_id` DESC')->limit($p->firstRow . ',' . $p->listRows)->select();

        $this->assign('village_list', $village_list);
        $pagebar = $p->show();
        $this->assign('pagebar', $pagebar);

        $this->display();
    }
	
	//小区添加
	public function village_add(){
		if(IS_POST){
			$_POST['pwd'] = md5($_POST['pwd']);
			$database_house_village = D('House_village');
			$_POST['add_time'] = time();
			if($database_house_village->data($_POST)->add()){
				$this->success('添加成功！');
			}else{
				$this->error('添加失败！请重试~');
			}
		}else{
			$this->display();
		}
	}
	//小区添加
	public function village_edit(){
		$database_house_village = D('House_village');
		if(IS_POST){
			if($_POST['pwd']){
				$_POST['pwd'] = md5($_POST['pwd']);
			}else{
				unset($_POST['pwd']);
			}
			if($database_house_village->data($_POST)->save()){
				$this->success('编辑成功！');
			}else{
				$this->error('编辑失败！请重试~');
			}
		}else{
			$now_village = $database_house_village->field(true)->where(array('village_id'=>$_GET['village_id']))->find();
			if(empty($now_village)){
				$this->frame_error_tips('当前小区不存在');
			}
			$this->assign('now_village',$now_village);
			
			$this->display();
		}
	}
	
	//小区导入
	public function village_import(){
		if(IS_POST){
			if ($_FILES['file']['error'] != 4) {
				$upload_dir = './upload/excel/village/'.date('Ymd').'/';
				if (!is_dir($upload_dir)) {
					mkdir($upload_dir, 0777, true);
				}
				import('ORG.Net.UploadFile');
				$upload = new UploadFile();
				$upload->maxSize = 10 * 1024 * 1024;
				$upload->allowExts = array('xls','xlsx');
				$upload->allowTypes = array(); // 允许上传的文件类型 留空不做检查
				$upload->savePath = $upload_dir;
				$upload->thumb = false;
				$upload->thumbType = 0;
				$upload->imageClassPath = '';
				$upload->thumbPrefix = '';
				$upload->saveRule = 'uniqid';
				if ($upload->upload()) {
					$uploadList = $upload->getUploadFileInfo();
					require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel/IOFactory.php';
					$path = $uploadList['0']['savepath'] . $uploadList['0']['savename'];
					$fileType = PHPExcel_IOFactory::identify($path); //文件名自动判断文件类型
					$objReader = PHPExcel_IOFactory::createReader($fileType);
					$excelObj = $objReader->load($path);
					$result = $excelObj->getActiveSheet()->toArray(null, true, true, true);
					if (!empty($result) && is_array($result)) {
						unset($result[1]);
						$database_house_village = D('House_village');
						$last_village_id = 0;
						foreach ($result as $kk => $vv) {
							if(empty($vv['A']) || empty($vv['B']) || empty($vv['C']) || empty($vv['D']) || empty($vv['E']) || empty($vv['F']) || empty($vv['G'])) continue;							
							$tmpdata = array();
							$tmpdata['village_name'] = htmlspecialchars(trim($vv['A']), ENT_QUOTES);
							//检测小区是否已存在
							if($database_house_village->field('`village_id`')->where(array('village_name'=>$tmpdata['village_name']))->find()) continue;
							
							$tmpdata['village_address'] = htmlspecialchars(trim($vv['B']), ENT_QUOTES);
							$tmpdata['property_name'] = htmlspecialchars(trim($vv['C']), ENT_QUOTES);
							$tmpdata['property_address'] = htmlspecialchars(trim($vv['D']), ENT_QUOTES);
							$tmpdata['property_phone'] = htmlspecialchars(trim($vv['E']), ENT_QUOTES);
							$tmpdata['account'] = htmlspecialchars(trim($vv['F']), ENT_QUOTES);
							$tmpdata['pwd'] = md5(htmlspecialchars(trim($vv['G']), ENT_QUOTES));
							!empty($vv['H']) && $tmpdata['property_price'] = htmlspecialchars(trim($vv['H']));
							!empty($vv['I']) && $tmpdata['water_price'] = htmlspecialchars(trim($vv['I']), ENT_QUOTES);
							!empty($vv['J']) && $tmpdata['electric_price'] = htmlspecialchars(trim($vv['J']));
							!empty($vv['K']) && $tmpdata['gas_price'] = htmlspecialchars(trim($vv['K']), ENT_QUOTES);
							!empty($vv['L']) && $tmpdata['park_price'] = htmlspecialchars(trim($vv['L']), ENT_QUOTES);
							$tmpdata['status'] = 0;
							$tmpdata['add_time'] = time();
							$last_village_id = $database_house_village->data($tmpdata)->add();
						}
						if(!empty($last_village_id)){
// 							$this->frame_submit_tips(1,'导入成功');
							$this->success('导入成功');
						}else{
							$this->error('导入失败');
// 							$this->frame_submit_tips(0,'导入失败');
						}
					}
				} else {
					$this->error($upload->getErrorMsg());
// 					$this->frame_submit_tips(0,$upload->getErrorMsg());
				}
			}
			$this->error('文件上传失败');
// 			$this->frame_submit_tips(0,'文件上传失败');
		}else{
			$this->display();
		}
	}

    public function edit() {
        $this->assign('bg_color', '#F3F3F3');

        $database_user = D('User');
        $condition_user['uid'] = intval($_GET['uid']);
        $now_user = $database_user->field(true)->where($condition_user)->find();
        if (empty($now_user)) {
            $this->frame_error_tips('没有找到该用户信息！');
        }

        $levelDb = M('User_level');
        $tmparr = $levelDb->field(true)->order('id ASC')->select();
        $levelarr = array();
        if ($tmparr) {
            foreach ($tmparr as $vv) {
                $levelarr[$vv['level']] = $vv;
            }
        }
		
        $this->assign('levelarr', $levelarr);
        $this->assign('now_user', $now_user);

        $this->display();
    }

    public function amend() {
        if (IS_POST) {
            $database_user = D('User');
            $condition_user['uid'] = intval($_POST['uid']);
            $now_user = $database_user->field(true)->where($condition_user)->find();
            if (empty($now_user)) {
                $this->error('没有找到该用户信息！');
            }
            $condition_user['uid'] = $now_user['uid'];
            $data_user['nickname'] = $_POST['nickname'];
            $data_user['phone'] = $_POST['phone'];
            if ($_POST['pwd']) {
                $data_user['pwd'] = md5($_POST['pwd']);
            }
            $data_user['sex'] = $_POST['sex'];
            $data_user['province'] = $_POST['province'];
            $data_user['city'] = $_POST['city'];
            $data_user['qq'] = $_POST['qq'];
            $data_user['status'] = $_POST['status'];
			$data_user['youaddress'] = trim($_POST['youaddress']);
			$data_user['truename'] = trim($_POST['truename']);
			
            $_POST['set_money'] = floatval($_POST['set_money']);
            if (!empty($_POST['set_money'])) {
                if ($_POST['set_money_type'] == 1) {
                    $data_user['now_money'] = $now_user['now_money'] + $_POST['set_money'];
                } else {
                    $data_user['now_money'] = $now_user['now_money'] - $_POST['set_money'];
                }
                if ($data_user['now_money'] < 0) {
                    $this->error('修改后，余额不能小于0');
                }
            }

            $_POST['set_score'] = intval($_POST['set_score']);
            if (!empty($_POST['set_score'])) {
                if ($_POST['set_score_type'] == 1) {
                    $data_user['score_count'] = $now_user['score_count'] + $_POST['set_score'];
                } else {
                    $data_user['score_count'] = $now_user['score_count'] - $_POST['set_score'];
                }
                if ($data_user['score_count'] < 0) {
                    $this->error('修改后，积分不能小于0');
                }
            }

            $data_user['level'] = intval($_POST['level']);

            if ($database_user->where($condition_user)->data($data_user)->save()) {
                if (!empty($_POST['set_money'])) {
                    D('User_money_list')->add_row($now_user['uid'], $_POST['set_money_type'], $_POST['set_money'], '管理员后台操作', false);
                }
                if (!empty($_POST['set_score'])) {
                    D('User_score_list')->add_row($now_user['uid'], $_POST['set_score_type'], $_POST['set_score'], '管理员后台操作', false);
                }
                $this->success('修改成功！');
            } else {
                $this->error('修改失败！请重试。');
            }
        } else {
            $this->error('非法访问！');
        }
    }

    public function money_list() {
        $this->assign('bg_color', '#F3F3F3');


        $database_user_money_list = D('User_money_list');
        $condition_user_money_list['uid'] = intval($_GET['uid']);

        $count = $database_user_money_list->where($condition_user_money_list)->count();
        import('@.ORG.system_page');
        $p = new Page($count, 15);

        $money_list = $database_user_money_list->field(true)->where($condition_user_money_list)->order('`time` DESC')->select();

        $this->assign('pagebar', $p->show());
        $this->assign('money_list', $money_list);
        $this->display();
    }

    public function score_list() {
        $this->assign('bg_color', '#F3F3F3');


        $database_user_score_list = D('User_score_list');
        $condition_user_score_list['uid'] = intval($_GET['uid']);

        $count = $database_user_score_list->where($condition_user_score_list)->count();
        import('@.ORG.system_page');
        $p = new Page($count, 15);

        $score_list = $database_user_score_list->field(true)->where($condition_user_score_list)->order('`time` DESC')->select();

        $this->assign('pagebar', $p->show());
        $this->assign('score_list', $score_list);
        $this->display();
    }

    /*     * *导入客户页**** */

    public function import() {

        $this->display();
    }

    /*     * *导入客户页**** */

    public function execimport() {
        if ($_FILES['file']['error'] != 4) {

            $getupload_dir = "/upload/excel/user/" . date('Ymd') . '/';
            $upload_dir = "." . $getupload_dir;
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            import('ORG.Net.UploadFile');
            $upload = new UploadFile();
            $upload->maxSize = 10 * 1024 * 1024;
            $upload->allowExts = array('xls', 'xlsx');
            $upload->allowTypes = array(); // 允许上传的文件类型 留空不做检查
            $upload->savePath = $upload_dir;
            $upload->thumb = false;
            $upload->thumbType = 0;
            $upload->imageClassPath = '';
            $upload->thumbPrefix = '';
            $upload->saveRule = 'uniqid';
            if ($upload->upload()) {
                $uploadList = $upload->getUploadFileInfo();
                require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel/IOFactory.php';
                $path = $uploadList['0']['savepath'] . $uploadList['0']['savename'];
                //$reader = PHPExcel_IOFactory::createReader('Excel5');
                $fileType = PHPExcel_IOFactory::identify($path); //文件名自动判断文件类型
                $objReader = PHPExcel_IOFactory::createReader($fileType);
                $excelObj = $objReader->load($path);
                $result = $excelObj->getActiveSheet()->toArray(null, true, true, true);
                if (!empty($result) && is_array($result)) {
                    unset($result[1]);
                    $user_importDb = D('User_import');
                    foreach ($result as $kk => $vv) {
                        if (empty($vv['A']) || empty($vv['B']) || empty($vv['C']))
                            continue;
                        $tmpdata = array();
                        $tmpdata['ppname'] = htmlspecialchars(trim($vv['A']), ENT_QUOTES);
                        $tmpdata['telphone'] = htmlspecialchars(trim($vv['B']), ENT_QUOTES);
                        $tmpdata['address'] = htmlspecialchars(trim($vv['C']), ENT_QUOTES);
                        !empty($vv['D']) && $tmpdata['mer_id'] = intval(trim($vv['D']));
                        !empty($vv['E']) && $tmpdata['memberid'] = htmlspecialchars(trim($vv['E']), ENT_QUOTES);
                        !empty($vv['F']) && $tmpdata['level'] = intval(trim($vv['F']));
                        !empty($vv['G']) && $tmpdata['qq'] = htmlspecialchars(trim($vv['G']), ENT_QUOTES);
                        !empty($vv['H']) && $tmpdata['email'] = htmlspecialchars(trim($vv['H']), ENT_QUOTES);
                        !empty($vv['I']) && $tmpdata['money'] = intval(trim($vv['I']));
                        !empty($vv['J']) && $tmpdata['integral'] = htmlspecialchars(trim($vv['J']), ENT_QUOTES);
                        !empty($vv['K']) && $tmpdata['useraccount'] = htmlspecialchars(trim($vv['K']), ENT_QUOTES);
                        if (!empty($vv['L'])) {
                            $tmpdata['pwdmw'] = trim($vv['L']);
                            $tmpdata['pwd'] = md5($tmpdata['pwdmw']);
                        }
                        $tmpdata['isuse'] = 0;
                        $tmpdata['addtime'] = time();
                        $user_importDb->add($tmpdata);
                    }
                    if (!empty($tmpdata)) {
                        $this->dexit(array('error' => 0));
                    } else {
                        $this->dexit(array('error' => 1, 'msg' => '导入失败！'));
                    }
                }
            } else {
                $this->dexit(array('error' => 1, 'msg' => $upload->getErrorMsg()));
            }
        }
        $this->dexit(array('error' => 1, 'msg' => '文件上传失败！'));
    }

    /*     * *导入客户的列表页**** */

    public function importlist() {
        $user_importDb = D('User_import');
        $count_userimportDb = $user_importDb->where('22=22')->count();
        import('@.ORG.system_page');
        $p = new Page($count_userimportDb, 20);
        $pagebar = $p->show();
        $this->assign('pagebar', $pagebar);
        $tmpdatas = $user_importDb->where('22=22')->order('id ASC')->limit($p->firstRow . ',' . $p->listRows)->select();
        $this->assign('userimport', $tmpdatas);
        $this->display();
    }

    /*     * *导入客户的列表页**** */

    public function levellist() {
        $user_levelDb = D('User_level');
        $count_userlevelDb = $user_levelDb->count();
        import('@.ORG.system_page');
        $p = new Page($count_userlevelDb, 20);
        $pagebar = $p->show();
        $this->assign('pagebar', $pagebar);
        $tmpdatas = $user_levelDb->where('22=22')->order('id ASC')->limit($p->firstRow . ',' . $p->listRows)->select();
        $this->assign('userlevel', $tmpdatas);
        $this->display();
    }

    /*     * *添加等级**** */

    public function addlevel() {
        $levelDb = M('User_level');
        $tmparr = $levelDb->where('22=22')->order('level DESC')->find();
        $level = 0;
        if (!empty($tmparr)) {
            $level = $tmparr['level'];
        }
        $level = $level + 1;
        if (IS_POST) {
            $lid = intval($_POST['lid']);
            if (!($lid > 0)) {
                $newdata = array('level' => $level);
            }
            $lname = trim($_POST['lname']);
            if (empty($lname))
                $this->error('等级名称没有填写！');
            $newdata['lname'] = $lname;

            $integral = intval($_POST['integral']);
            if (!($integral > 0))
                $this->error('等级积分没有填写！');
            $newdata['integral'] = $integral;

            $newdata['icon'] = trim($_POST['icon']);
            $newdata['type'] = trim($_POST['fltype']);
            $newdata['boon'] = trim($_POST['boon']);
            $newdata['description'] = trim($_POST['description']);

            if ($lid > 0) {
                $inser_id = $levelDb->where(array('id' => $lid))->save($newdata);
            } else {
                $inser_id = $levelDb->add($newdata);
            }
            if ($inser_id) {
                $this->success('保存成功！');
            } else {
                $this->error('保存失败！');
            }
        } else {
            $lid = intval($_GET['lid']);
            $tmpdata = $levelDb->where(array('id' => $lid))->find();
            if (empty($tmpdata)) {
                $tmpdata = array('id' => 0, 'level' => $level, 'lname' => '', 'integral' => '', 'icon' => '', 'boon' => '', 'type' => 0, 'description' => '');
            }
            $this->assign('leveldata', $tmpdata);
            $this->display();
        }
    }

    public function pay_order(){
    	$village_id = $_GET['village_id'];
    	if($village_id){
    		$condition['village_id'] = $village_id;
    		$condition['paid'] = 1;
    		$result = D('House_village_pay_order')->get_limit_list_page($condition,20,true);
    		
    		$finshtotal = $total = 0;
    		if($result){
    			foreach ($result['order_list'] as $v){
    				$total += $v['money'];								//本页的总额
    				$v['is_pay_bill'] && $finshtotal += $v['money'];	//本页已对账的总额
    			}
    		}
    		$this->assign('finshtotal',$finshtotal);
    		$this->assign('total',$total);
    		$this->assign('order_list',$result);
    	}
    	$this->assign('village_id',$village_id);
    	$this->display();
    }
    
    public function change(){
    	$village_id = $_POST['village_id'];
    	$strids = isset($_POST['strids']) ? htmlspecialchars($_POST['strids']) : '';
    	if ($strids && $village_id) {
    		$array = explode(',', $strids);
    		$usernums = $orderids = array();
    		foreach ($array as $val) {
    			$t = explode('_', $val);
    			if ($t[1]) {
    				$orderids[] = $t[1];
    			}
    		}
   
    		$orderids && D('House_village_pay_order')->where(array('village_id' => $village_id, 'order_id' => array('in', $orderids)))->save(array('is_pay_bill' => 1));
    	}
    	exit(json_encode(array('error_code' => 0)));
    }
    
    /*     * **删除一条导入的记录**** */

    function delimportuser() {
        $idx = (int) trim($_POST['id']);
        $user_importDb = D('User_import');
        if ($user_importDb->where(array('id' => $idx))->delete()) {
            $this->dexit(array('error' => 0));
        } else {
            $this->dexit(array('error' => 1));
        }
    }

    /*     * json 格式封装函数* */

    private function dexit($data = '') {
        if (is_array($data)) {
            echo json_encode($data);
        } else {
            echo $data;
        }
        exit();
    }
	public function village_login(){
		$database_village = D('House_village');
		$condition_village['village_id'] = $_GET['village_id'];
		$now_village = $database_village->field(true)->where($condition_village)->find();
		if(empty($now_village) || $now_village['status'] == 2){
			exit('<html><head><script>window.top.toggleMenu(0);window.top.msg(0,"该小区的状态不存在！请查阅。",true,5);window.history.back();</script></head></html>');
		}
		session('house',$now_village);
		$script_name = trim($_SERVER['SCRIPT_NAME'],'/');
		if($_GET['group_id']){
			redirect($this->config['site_url'].'/shequ.php?c=Group&a=frame_edit&group_id='.$_GET['group_id'].'&system_file='.$script_name);
		}else if($_GET['activity_id']){
			redirect($this->config['site_url'].'/shequ.php?c=Activity&a=frame_edit&id='.$_GET['activity_id'].'&system_file='.$script_name);
		}else if($_GET['appoint_id']){
			redirect($this->config['site_url'].'/shequ.php?c=Appoint&a=frame_edit&appoint_id='.$_GET['appoint_id'].'&system_file='.$script_name);
		}else{
			redirect($this->config['site_url'].'/shequ.php');
		}
	}
}
