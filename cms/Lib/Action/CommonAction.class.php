<?php

function terfas4jGFFDSA23fsdafadsindexrfdsfsadfsGDSdfasd()
{
}

function utyjfsldDSAqkfjlfdslkjfldsawapfjdslakfHDFfjlsaf()
{
}

function uytuytskjqewFSDAjkcnbafklzfsdauserkfdnlasDSAskfaf()
{
}

function uitreuitrewhjkfgdkjnlsfgdjklnfadsSYStemfsdajlgfd()
{
}

function tlrewkhtnlerwkjtlkReleasefljdsknfglasdkjnflskad()
{
}

function rlbklfdsakljdfsakjldfsMerchantkjlfjklfdasjklfads()
{
}

function klfjndslkajfoiwqjeroiwqjoiMeallkfjasdlkfjaslknklbklnqqio()
{
}

function fksjdfalkjadslkfjasdlfkjasdlfkjasdlkfjaslkLotteryfdlkjfasl()
{
}

function fdksajflkjsadlkjblkfndlqkwtnGroupqwlkrIndexqwrewqrmbvlknasdfa()
{
}

class CommonAction extends Action
{
	protected $user_session;
	protected $config;
	protected $common_url;
	protected $static_path;
	protected $static_public;
	protected $user_level;

	protected function _initialize()
	{
		$serverHost = '';

		if (function_exists('getallheaders')) {
			$allheaders = getallheaders();
			$serverHost = $allheaders['Host'];
		}

		if (empty($serverHost)) {
			$serverHost = $_SERVER['HTTP_HOST'];
		}

		$this->config = D('Config')->get_config();
		/*$authorizeReturnInt = intval($authorizeReturn);
		if (is_numeric($authorizeReturn) && (1 < $authorizeReturnInt)) {
			$this->config['now_city'] = $authorizeReturnInt;
		}*/

		if ((GROUP_NAME != 'Wap') && (($this->config['site_close'] == 1) || ($this->config['site_close'] == 3))) {
			$this->assign('title', '网站关闭');
			$this->assign('jumpUrl', '-1');
			$this->error($this->config['site_close_reason'] ? $this->config['site_close_reason'] : '网站临时关闭');
		}
		     

		$this->config['config_site_url'] = $this->config['site_url'];
		//$this->config['site_url'] = 'http://' . $_SERVER['HTTP_HOST'];
		$url = 'http://' . $_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];
		$this->config['site_url'] = dirname($url);

		
		     
		$this->assign('config', $this->config);
		if ((strtolower(GROUP_NAME) != 'wap') && !C('THEME_LOCK') && $this->config['site_web_tpl']) {
			C('DEFAULT_THEME', $this->config['site_web_tpl']);
		}
		else {
			if ((strtolower(GROUP_NAME) == 'wap') && !C('THEME_LOCK') && $this->config['site_wap_tpl']) {
				if ($this->config['site_wap_tpl'] == 'pure') {
					if ($_COOKIE['lowPhoneVersion'] != '1') {
						$pureTheme = array('homeindex', 'searchindex', 'searchgroup', 'searchmeal', 'searchappoint', 'groupindex', 'groupdetail', 'groupbranch', 'groupmap', 'groupshop', 'groupaddressinfo', 'groupget_route', 'groupfeedback', 'merchantaround', 'meal_listindex', 'appointindex', 'changecityindex', 'housevillage_list', 'housevillage', 'housevillage_newslist', 'housevillage_news', 'housevillage_grouplist', 'housevillage_meallist', 'housevillage_appointlist', 'housevillage_pay', 'housevillage_my', 'housevillage_my_pay', 'housevillage_my_paylists', 'housevillage_my_repair', 'housevillage_my_utilities', 'housevillage_my_suggest', 'housevillage_my_repairlists', 'housevillage_my_repair_detail', 'housevillage_my_utilitieslists', 'housevillage_my_utilities_detail', 'housevillage_select');

						if (!in_array(strtolower(MODULE_NAME) . strtolower(ACTION_NAME), $pureTheme)) {
							$this->assign('no_footer', true);
							C('DEFAULT_THEME', 'default');
						}
						else {
							C('DEFAULT_THEME', $this->config['site_wap_tpl']);
						}
					}
					else {
						C('DEFAULT_THEME', 'default');
					}
				}
				else {
					C('DEFAULT_THEME', $this->config['site_wap_tpl']);
				}
			}
		}

		C('config', $this->config);
		session_start();
		$this->user_session = session('user');
		$this->assign('user_session', $this->user_session);
		$levelDb = M('User_level');
		$tmparr = $levelDb->field(true)->order('`id` ASC')->select();
		$levelarr = array();

		if ($tmparr) {
			foreach ($tmparr as $vv) {
				$levelarr[$vv['level']] = $vv;
			}
		}

		$this->user_level = $levelarr;
		unset($tmparr);
		unset($levelarr);
		$this->assign('levelarr', $this->user_level);
		$this->common_url['group_category_all'] = $this->config['site_url'] . '/category/all/all';
		$this->static_path = $this->config['site_url'] . '/static/' . C('DEFAULT_THEME') . '/';
		$this->static_public = $this->config['site_url'] . '/static/';
		$this->assign('static_path', $this->static_path);
		$this->assign('static_public', $this->static_public);
		     
		$this->assign($this->common_url);
	}

	protected function get_uri_param()
	{
		$uri_arr = explode('?', $_SERVER['REQUEST_URI']);

		if (!empty($uri_arr[1])) {
			$uri_tmp = explode('&', $uri_arr[1]);

			foreach ($uri_tmp as $key => $value) {
				$tmp_arr = explode('=', $value);
				$return[$tmp_arr[0]] = $tmp_arr[1];
			}

			return $return;
		}

		return false;
	}

	protected function header_json()
	{
		header('Content-type: application/json');
	}

	protected function error_tips($msg, $url)
	{
		$this->assign('jumpUrl', $url);
		$this->error($msg);
	}

	protected function editor_alert($msg)
	{
		exit(json_encode(array('error' => 1, 'message' => $msg)));
	}

	protected function ok_jsonp_return($json_arr)
	{
		$json_arr['err_code'] = 0;
		exit($_GET['callback'] . '(' . json_encode($json_arr) . ')');
	}

	public function get_encrypt_key($array, $app_key)
	{
		$new_arr = array();
		ksort($array);

		foreach ($array as $key => $value) {
			$new_arr[] = $key . '=' . $value;
		}

		$new_arr[] = 'app_key=' . $app_key;
		$string = implode('&', $new_arr);
		return md5($string);
	}

	protected function get_im_encrypt_key($array, $app_key)
	{
		$new_arr = array();
		ksort($array);

		foreach ($array as $key => $value) {
			$new_arr[] = $key . '=' . $value;
		}

		$new_arr[] = 'app_key=' . $app_key;
		$string = implode('&', $new_arr);
		return md5($string);
	}

	protected function wapFriendRange($meter)
	{
		if ($meter < 100) {
			return '<100m';
		}

		if ($meter < 1000) {
			return $meter . 'm';
		}

		return round($meter / 1000, 1) . 'km';
	}
	
}

?>
