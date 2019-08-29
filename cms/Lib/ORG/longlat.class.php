<?php
/* 
 * 经纬度转换
 * 
 */
class longlat{
	/**
       * 腾讯地图坐标转百度地图坐标
       * @param [String] $lat 腾讯地图坐标的纬度
       * @param [String] $lng 腾讯地图坐标的经度
       * @return [Array] 返回记录纬度经度的数组
	*/
	function gpsToBaidu($lat,$lng){
		$x_pi = 3.14159265358979324 * 3000.0 / 180.0;
		$x = $lng;
		$y = $lat;
		$z = sqrt($x * $x + $y * $y) + 0.00002 * sin($y * $x_pi);
		$theta = atan2($y, $x) + 0.000003 * cos($x * $x_pi);
		$lng = $z * cos($theta) + 0.0065;
		$lat = $z * sin($theta) + 0.006;
		return array('lng'=>$lng,'lat'=>$lat);
	}
	//百度地图坐标计算
	function rad($d){  
		   return $d * 3.1415926535898 / 180.0;  
	}
	/**
       * 百度地图坐标计算两点之间的距离
       * @param [String] $lat1 A点的纬度
       * @param [String] $lng1 A点的经度
       * @param [String] $lat2 B点的纬度
       * @param [String] $lng2 B点的经度
       * @return [String] 两点坐标间的距离，输出单位为米
	*/
	function GetDistance($lat1,$lng1,$lat2,$lng2){
	   $EARTH_RADIUS = 6378.137;//地球的半径
	   $radLat1 = rad($lat1);   
	   $radLat2 = rad($lat2);  
	   $a = $radLat1 - $radLat2;  
	   $b = rad($lng1) - rad($lng2);  
	   $s = 2 * asin(sqrt(pow(sin($a/2),2) + cos($radLat1)*cos($radLat2)*pow(sin($b/2),2)));  
	   $s = $s *$EARTH_RADIUS;  
	   $s = round($s * 10000) / 10000;
	   $s=$s*1000;
	   return ceil($s);  
	}
	/**
       * 标记大概的距离，做出友好的距离提示
       * @param [$number] 距离数量
       * @return[String] 距离提示
	*/
	function mToKm($range){
		$return = array();
		if($range < 100){
			$return['num'] = $range;
			$return['unit'] = 'm';
			$return['cunit'] = '米';
		}elseif($range < 1000){
			$return['num'] = round($range,1);
			$return['unit'] = 'km';
			$return['cunit'] = '千米';
		}else if(range<5000){
			$return['num'] = round($range,2);
			$return['unit'] = 'km';
			$return['cunit'] = '千米';
		}else if(range<10000){
			$return['num'] = round($range,1);
			$return['unit'] = 'km';
			$return['cunit'] = '千米';
		}else{
			$return['num'] = floor($range/1000);
			$return['unit'] = 'km';
			$return['cunit'] = '千米';
		}
		return $return;
	}
}
?>