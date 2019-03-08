<?php
/* 
 * 生活服务的类
 * 
 */
class live_service{
    public function create(){
		$array = parse_url(C('config.site_url'));

		$data = array(
			'domain' => $array['host'],
			'from' => '2',
			'city_id' => C('config.now_city'),
		);
		
		import('ORG.Net.Http');
		$http = new Http();
		
        $return = Http::curlPost('http://life-service.meihua.com/api/app_create.php', $data);

		if($return['err_code']){
			if($return['err_code'] == 1003){
				if (D('Config')->where("`name`='live_service_have'")->find()) {
					D('Config')->where("`name`='live_service_have'")->save(array('value' => '0'));
				} else {
					D('Config')->add(array('name' => 'live_service_have', 'value' => '0', 'gid' => 0, 'status' => 1));
				}
			}
			exit(json_encode(array('error_code' => true, 'msg' => $return['err_msg'])));
		} else {
			if (D('Config')->where("`name`='live_service_type'")->find()) {
				D('Config')->where("`name`='live_service_type'")->save(array('value' => $return['service_type']));
			} else {
				D('Config')->add(array('name' => 'live_service_type', 'value' => $return['service_type'], 'gid' => 0, 'status' => 1));
			}
			if (D('Config')->where("`name`='live_service_have'")->find()) {
				D('Config')->where("`name`='live_service_have'")->save(array('value' => '1'));
			} else {
				D('Config')->add(array('name' => 'live_service_have', 'value' => '1', 'gid' => 0, 'status' => 1));
			}
			if (D('Config')->where("`name`='live_service_appid'")->find()) {
				D('Config')->where("`name`='live_service_appid'")->save(array('value' => $return['app_id']));
			} else {
				D('Config')->add(array('name' => 'live_service_appid', 'value' => $return['app_id'], 'gid' => 0, 'status' => 1));
			}
			if (D('Config')->where("`name`='live_service_appkey'")->find()) {
				D('Config')->where("`name`='live_service_appkey'")->save(array('value' => $return['app_key']));
			} else {
				D('Config')->add(array('name' => 'live_service_appkey', 'value' => $return['app_key'], 'gid' => 0, 'status' => 1));
			}
			S('config',null);
			exit(json_encode(array('error_code' => false, 'msg' => '获取成功')));
		}
    }
}
?>