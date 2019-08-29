<?php
class User_long_latModel extends Model{
	/*保存地理位置*/
	public function saveLocation($openid,$long,$lat){
		if($openid){
			if ($this->field(true)->where(array('open_id' => $openid))->find()) {
				$this->where(array('open_id' => $openid))->save(array('long' => $long, 'lat' => $lat, 'dateline' => $_SERVER['REQUEST_TIME']));
			} else {
				$this->add(array('long' => $long, 'lat' => $lat, 'dateline' => $_SERVER['REQUEST_TIME'], 'open_id' => $openid));
			}
			if(C('config.im_appid')){
				//20分钟一次推送
				if(empty($_SESSION['last_im_time']) || $_SESSION['last_im_time'] - $_SERVER['REQUEST_TIME'] < 1200){
					$im = new im();
					$im->saveLocation($openid,$long,$lat);
					$_SESSION['last_im_time'] = $_SERVER['REQUEST_TIME'];
				}
			}
		}else{
			return array('errCode'=>true,'errMsg'=>'没有携带openid');
		}
	}
	/*
	 * 得到地理位置
	 *
	 * 时效120秒
	 *
	 * 存的是 GPS定位，系统使用的是百度地图，进行转换
	 * 
	*/
	public function getLocation($openid,$timeout=120){
		if($openid){
			$user_long_lat = $this->where(array('open_id' => $openid))->find();
			if($user_long_lat && $user_long_lat['long']){
				if($timeout != 0 && $user_long_lat['dateline'] < $_SERVER['REQUEST_TIME'] - $timeout){
					return array();
				}
				import('@.ORG.longlat');
				$longlat_class = new longlat();
				$location2 = $longlat_class->gpsToBaidu($user_long_lat['lat'], $user_long_lat['long']);
				return array('long'=>$location2['lng'],'lat'=>$location2['lat'],'dateline'=>$user_long_lat['dateline']);
			}else{
				return array();
			}
		}else{
			if($_COOKIE['userLocationLong'] && $_COOKIE['userLocationLat']){
				import('@.ORG.longlat');
				$longlat_class = new longlat();
				$location2 = $longlat_class->gpsToBaidu($_COOKIE['userLocationLat'], $_COOKIE['userLocationLong']);
				return array('long'=>$location2['lng'],'lat'=>$location2['lat'],'dateline'=>$_SERVER['REQUEST_TIME']);
			}
			return array();
		}
	}
}
?>