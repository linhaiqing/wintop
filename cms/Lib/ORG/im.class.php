<?php
class im
{
	public function create()
	{
		$array = parse_url(C('config.site_url'));
		$data = array('domain' => $array['host'], 'label' => '', 'from' => '2', 'wx_app_id' => C('config.wechat_appid'), 'wx_app_secret' => C('config.wechat_appsecret'), 'activity_url' => C('config.site_url') . '/wap.php?g=Wap&c=Api&a=activity', 'my_url' => C('config.site_url') . '/wap.php?g=Wap&c=Api&a=my', 'msg_tip_url' => C('config.site_url') . '/wap.php?g=Wap&c=Api&a=index');
		import('ORG.Net.Http');
		$http = new Http();
		$return = Http::curlPost('http://im-link.meihua.com/api/app_create.php', $data);

		if ($return['err_code']) {
			exit(json_encode(array('error_code' => true, 'msg' => $return['err_msg'])));
		}
		else {
			if (D('Config')->where('`name`=\'im_appid\'')->find()) {
				D('Config')->where('`name`=\'im_appid\'')->save(array('value' => $return['app_id']));
			}
			else {
				D('Config')->add(array('name' => 'im_appid', 'value' => $return['app_id'], 'gid' => 0, 'status' => 1));
			}

			if (D('Config')->where('`name`=\'im_appkey\'')->find()) {
				D('Config')->where('`name`=\'im_appkey\'')->save(array('value' => $return['app_key']));
			}
			else {
				D('Config')->add(array('name' => 'im_appkey', 'value' => $return['app_key'], 'gid' => 0, 'status' => 1));
			}

			S('config', NULL);
			exit(json_encode(array('error_code' => false, 'msg' => '获取成功')));
		}
	}
}


?>
