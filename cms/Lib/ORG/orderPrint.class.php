<?php
class orderPrint {

	public $serverUrl;
	public $key;
	public $topdomain;
	public $token;
	public function __construct($token){
		$this->serverUrl='';//'http://up.pigcms.cn/';
		$this->key=trim(C('server_key'));
		$this->topdomain=trim(C('server_topdomain'));
		if (!$this->topdomain){
			$this->topdomain=$this->getTopDomain();
		}
		$this->token=$token;
	}
	public function printit($mer_id, $store_id = 0, $content = '', $paid = 0, $qr = ''){
	
		$usePrinters = D('Orderprinter')->where(array('mer_id' => $mer_id, 'store_id' => $store_id))->find();
		//dump($usePrinters);
		if ($usePrinters){
			if (!$usePrinters['paid'] || ($usePrinters['paid'] && $paid)){
				
				if ($usePrinters['mp']) {
					$datart = array('content' => $content, 'machine_code' => $usePrinters['mcode'], 'machine_key' => $usePrinters['mkey']);				
					$msg          = $datart['content']; //打印内容
					$apiKey       = $usePrinters['mp'];//apiKey
					$mKey         = $datart['machine_key'];//秘钥
					$partner      = $usePrinters['username'];//用户id
					$machine_code = $datart['machine_code'];//打印机终端号
					$ti = time();
					$params = array(
								  'partner'=>$partner,
								  'machine_code'=>$machine_code,
								  'time'=>$ti
				
					);
					$sign = $this->generateSign($params,$apiKey,$mKey);

					$params['sign'] = $sign;
					$params['content'] = $msg;


					$url = 'open.10ss.net:8888';//接口端点

					$p = '';
					foreach ($params as $k => $v) {
						$p .= $k.'='.$v.'&';
					}
					$data = rtrim($p, '&');
					$rt= $this->api_notice_increment($url,$data);
					//$rt = $this->api_notice_increment($url, $data);
				} else {
					
					
					/*$data = array('content' => '|5' . $content);
					if ($qr == '') {
						$qrlink = $usePrinters['qrcode'];
					} else {
						$qrlink = $qr;
					}
					$url = $this->serverUrl.'server.php?m=server&c=orderPrint&a=fcprintit&productid=3&count='.$usePrinters['count'].'&mkey='.$usePrinters['mkey'].'&mcode='.$usePrinters['mcode'].'&name='.$usePrinters['username'].'&qr='.urlencode($qrlink).'&domain='.$this->topdomain;
					$rt = $this->api_notice_increment($url, $data);*/
				}

			}
		}
		
		
		
	}
	
	public function generateSign($params, $apiKey, $msign)
{
    //所有请求参数按照字母先后顺序排
    ksort($params);
    //定义字符串开始所包括的字符串
    $stringToBeSigned = $apiKey;
    //把所有参数名和参数值串在一起
    foreach ($params as $k => $v)
    {
        $stringToBeSigned .= urldecode($k.$v);
    }
    unset($k, $v);
    //定义字符串结尾所包括的字符串
    $stringToBeSigned .= $msign;
    //使用MD5进行加密，再转化成大写
    return strtoupper(md5($stringToBeSigned));
}
	
	public function api_notice_increment($url, $data){
		$ch = curl_init();
		$header = "Accept-Charset: utf-8";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_SSLVERSION, 3);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$tmpInfo = curl_exec($ch);
		$errorno=curl_errno($ch);
		if ($errorno) {
			return $errorno;
		}else{
			return $tmpInfo;
		}
	}
	function getTopDomain(){
		$host=$_SERVER['HTTP_HOST'];
		$host=strtolower($host);
		if(strpos($host,'/')!==false){
			$parse = @parse_url($host);
			$host = $parse['host'];
		}
		$topleveldomaindb=array('com','edu','gov','int','mil','net','org','biz','info','pro','name','museum','coop','aero','xxx','idv','mobi','cc','me');
		$str='';
		foreach($topleveldomaindb as $v){
			$str.=($str ? '|' : '').$v;
		}
		$matchstr="[^\.]+\.(?:(".$str.")|\w{2}|((".$str.")\.\w{2}))$";
		if(preg_match("/".$matchstr."/ies",$host,$matchs)){
			$domain=$matchs['0'];
		}else{
			$domain=$host;
		}
		return $domain;
	}
}
