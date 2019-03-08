<?php
/*
 * 后台登录
 *
 * @  Writers    Jaty
 * @  BuildTime  2014/11/05 15:28
 * 
 */

class LoginAction extends BaseAction{
    public function index(){
		$this->display();
    }
	public function check(){
		$verify = $this->_post('verify');
		if(md5($verify) != $_SESSION['admin_verify']){
			exit('-1');
		}
		
		$database_admin = D('Admin');
		$condition_admin['account'] = $this->_post('account');
		$condition_admin['status'] = 1;
		$now_admin = $database_admin->field(true)->where($condition_admin)->find();
		if(empty($now_admin)){
			exit('-2');
		}
		$pwd = $this->_post('pwd','htmlspecialchars,md5');
		if($pwd != $now_admin['pwd']){
			exit('-3');
		}
		if($now_admin['status'] != 1){
			exit('-4');
		}
		$now_admin['show_account'] = '超级管理员';
		if ($now_admin['level'] == 1) {
			if ($now_admin['area_id']) {
				$area = D('Area')->field(true)->where(array('area_id' => $now_admin['area_id']))->find();
				$now_admin['show_account'] = $area['area_name'] . '管理员';
			}
		} else {
			$now_admin['show_account'] = '普通管理员';
		}
		
		$data_admin['id'] = $now_admin['id'];
		$data_admin['last_ip'] = get_client_ip(1);
		$data_admin['last_time'] = $_SERVER['REQUEST_TIME'];
		$data_admin['login_count'] = $now_admin['login_count']+1;
		if($database_admin->data($data_admin)->save()){
			$now_admin['login_count'] += 1;
			if(!empty($now_admin['last_ip'])){
				import('ORG.Net.IpLocation');
				$IpLocation = new IpLocation();
				$last_location = $IpLocation->getlocation(long2ip($now_admin['last_ip']));
				$now_admin['last']['country'] = iconv('GBK','UTF-8',$last_location['country']);
				$now_admin['last']['area'] = iconv('GBK','UTF-8',$last_location['area']);
			}
			session('system',$now_admin);
			exit('1');
		}else{
			exit('-5');
		}
	}
	public function logout(){
		session('system',null);
		header('Location: '.U('Login/index'));
	}
	public function verify(){
		import('ORG.Util.Image');
		Image::buildImageVerify(4,1,'jpeg',53,26,'admin_verify');
	}
}