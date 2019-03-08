<?php 
class WechatShare 
{
	private $appId		= '';
	private $appSecret	= '';
	public $error 		= array();
	public $token 		= '';  
	public $wecha_id 		= '';
	
	public $share_ticket = '';
	public $share_dated = 0;
	public $mer_id = null;
	
	//构造函数获取access_token
	function __construct($config, $wechat_id = ''){
		
		$this->appId		= isset($config['wechat_appid']) ? $config['wechat_appid'] : '';
		$this->appSecret	= isset($config['wechat_appsecret']) ? $config['wechat_appsecret'] : '';
		$this->token	= isset($config['wechat_token']) ? $config['wechat_token'] : '';
		$this->share_ticket	= isset($config['share_ticket']) ? $config['share_ticket'] : '';
		$this->share_dated	= isset($config['share_dated']) ? $config['share_dated'] : 0;
		$this->wecha_id	= $wechat_id;
		
	}

	public function getSgin()
	{
		$this->checkTicket();
		//$url = $this->getUrl();
		$url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

		$sign_data = $this->addSign($this->share_ticket, $url);
		$share_html = $this->createHtml($sign_data);

		return $share_html;
	}
	
	public function checkTicket()
	{
		$now = time();
		if (empty($this->share_ticket) || $this->share_dated < $now ) {
			if(C('open_authorize_wxpay')){
				$tokenData 	= D('Weixin_bind')->get_access_token($mer_id);
			}else{
				$tokenData 	= D('Access_token_expires')->get_access_token();//$this->getToken();
			}
			
			if($tokenData['errcode']){
				$this->error['token_error'] 	= array('errcode'=>$tokenData['errcode'],'errmsg'=>$tokenData['errmsg']);
			}else{
				$access_token 	= $tokenData['access_token'];
				$ticketData 	= $this->getTicket($access_token);
				if($ticketData['errcode']>0){
					$this->error['ticket_error'] = array('errcode' => $ticketData['errcode'], 'errmsg' => $ticketData['errmsg']);
				}else{
					$this->share_ticket = $ticket = $ticketData['ticket'];
					if ($config = D('Config')->field('name, value')->where(array('name' => 'share_ticket'))->find()) {
						D('Config')->where(array('name' => 'share_ticket'))->save(array('value' => $ticketData['ticket']));
					} else {
						D('Config')->add(array('name' => 'share_ticket', 'value' => $ticketData['ticket'], 'gid' => 0));
					}
					$this->share_dated = $now + $ticketData['expires_in'];
					if ($config = D('Config')->field('name, value')->where(array('name' => 'share_dated'))->find()) {
						D('Config')->where(array('name' => 'share_dated'))->save(array('value' => $this->share_dated));
					} else {
						D('Config')->add(array('name' => 'share_dated', 'value' => $this->share_dated, 'gid' => 0));
					}
					S('config',null);
				}
			}
		}
	}

	public function gethideOptionMenu($mer_id = null)
	{
		$this->mer_id = $mer_id;
		$this->checkTicket();
		$url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

		$sign_data = $this->addSign($this->share_ticket, $url);

		$hide_html = $this->hideOptionMenu($sign_data);

		return $hide_html;
	}

	public function getError(){
		dump($this->error);
	}
	
	public function addSign($ticket,$url){
		$timestamp = time();
		$nonceStr  = rand(100000,999999);
		$array 	= array(
			"noncestr"		=> $nonceStr,		
			"jsapi_ticket"	=> $ticket,
			"timestamp"		=> $timestamp,
			"url"			=> $url,
		);
		
		ksort($array);
		$signPars	= '';
	
		foreach($array as $k => $v) {
			if("" != $v && "sign" != $k) {
				if($signPars == ''){
					$signPars .= $k . "=" . $v;
				}else{
					$signPars .=  "&". $k . "=" . $v;
				}
			}
		}
		
		$result = array(
			'appId' 	=> $this->appId,
			'timestamp' => $timestamp,
			'nonceStr'  => $nonceStr,
			'url' 		=> $url,
			'signature'  => SHA1($signPars),
		);
		
		return $result;
	}



	public function getUrl(){
 		$url 	= "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

		if(isset($_GET['code']) && isset($_GET['state']) && ($_GET['state'] == 'oauth')){
			$url 		= $this->clearUrl($url);
			if(isset($_GET['wecha_id'])){
				$url .= '&wecha_id='.$this->wecha_id;
			}
			return $url;
		}else{
			return $url;
		}

	}
	
	public function clearUrl($url){
		$param 	= explode('&', $url);
		for ($i=0,$count=count($param); $i < $count; $i++) {
			if(preg_match('/^(code=|state=|wecha_id=).*/', $param[$i])){
				unset($param[$i]);
			}
		}
		return join('&',$param);
	}
	
	//获取token
	public function  getToken(){
		return D('Access_token_expires')->get_access_token();
	}

	public function getTicket($token){
		$url 	= "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=".$token."&type=jsapi";
		return $this->https_request($url);
	}


	/*创建分享html*/
	public function hideOptionMenu($sign_data){

	$html 	= <<<EOM
	<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
	<script type="text/javascript">
		wx.config({
		  debug: false,
		  appId: 	'{$sign_data['appId']}',
		  timestamp: {$sign_data['timestamp']},
		  nonceStr: '{$sign_data['nonceStr']}',
		  signature: '{$sign_data['signature']}',
		  jsApiList: [
		    'checkJsApi',
		    'onMenuShareTimeline',
		    'onMenuShareAppMessage',
		    'onMenuShareQQ',
		    'onMenuShareWeibo',
		    'scanQRCode',
		    'previewImage'
		  ]
		});
	</script>
	<script type="text/javascript">
	wx.ready(function(){wx.hideOptionMenu();});
	</script>
EOM;
		return $html;
	}


	/*创建分享html*/
	public function createHtml($sign_data){

	$html 	= <<<EOM
	<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
	<script type="text/javascript">
		wx.config({
		  debug: false,
		  appId: 	'{$sign_data['appId']}',
		  timestamp: {$sign_data['timestamp']},
		  nonceStr: '{$sign_data['nonceStr']}',
		  signature: '{$sign_data['signature']}',
		  jsApiList: [
		    'checkJsApi',
		    'onMenuShareTimeline',
		    'onMenuShareAppMessage',
		    'onMenuShareQQ',
		    'onMenuShareWeibo',
		    'scanQRCode',
			'chooseImage',
			'previewImage',
			'uploadImage',
			'downloadImage',
			'getLocation',
			'openLocation',
			'getNetworkType',
			'startRecord',
			'stopRecord',
			'onVoiceRecordEnd',
			'playVoice',
			'translateVoice',
		  ]
		});
	</script>
	<script type="text/javascript">
	wx.ready(function () {
	  // 1 判断当前版本是否支持指定 JS 接口，支持批量判断
	  /*document.querySelector('#checkJsApi').onclick = function () {
	    wx.checkJsApi({
	      jsApiList: [
	        'getNetworkType',
	        'previewImage'
	      ],
	      success: function (res) {
	        //alert(JSON.stringify(res));
	      }
	    });
	  };*/

	  // 2. 分享接口
	  // 2.1 监听“分享给朋友”，按钮点击、自定义分享内容及分享结果接口
	    wx.onMenuShareAppMessage({
			title: window.shareData.tTitle,
			desc: window.shareData.tContent,
			link: window.shareData.sendFriendLink + '&openid=' + '{$this->wecha_id}',
			imgUrl: window.shareData.imgUrl,
		    type: '', // 分享类型,music、video或link，不填默认为link
		    dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
		    success: function () { 
				shareHandle('frined');
		        //alert('分享朋友成功');
		    },
		    cancel: function () { 
		        //alert('分享朋友失败');
		    }
		});


	  // 2.2 监听“分享到朋友圈”按钮点击、自定义分享内容及分享结果接口
		wx.onMenuShareTimeline({
			title: window.shareData.tTitle,
			link: window.shareData.sendFriendLink + '&openid=' + '{$this->wecha_id}',
			imgUrl: window.shareData.imgUrl,
		    success: function () { 
				shareHandle('frineds');
		        //alert('分享朋友圈成功');
		    },
		    cancel: function () { 
		        //alert('分享朋友圈失败');
		    }
		});	

	  // 2.4 监听“分享到微博”按钮点击、自定义分享内容及分享结果接口
		wx.onMenuShareWeibo({
			title: window.shareData.tTitle,
			desc: window.shareData.tContent,
			link: window.shareData.sendFriendLink + '&openid=' + '{$this->wecha_id}',
			imgUrl: window.shareData.imgUrl,
		    success: function () { 
				shareHandle('weibo');
		       	//alert('分享微博成功');
		    },
		    cancel: function () { 
		        //alert('分享微博失败');
		    }
		});
		
	});
		
	function shareHandle(to) {
		var submitData = {
			module: window.shareData.moduleName,
			moduleid: window.shareData.moduleID,
			token:'{$this->token}',
			wecha_id:'{$this->wecha_id}',
			url: window.shareData.sendFriendLink,
			to:to
		};
		
	}
</script>
EOM;
		return $html;
	}
	//$.post('index.php?g=Wap&m=Share&a=shareData&token={$this->token}&wecha_id={$this->wecha_id}',submitData,function (data) {},'json')
	//https请求（支持GET和POST）
	protected function https_request($url, $data = null)
	{
		$curl = curl_init();
		$header = "Accept-Charset: utf-8";
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
		//curl_setopt($curl, CURLOPT_SSLVERSION, 3);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		if (!empty($data)){
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		}
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($curl);
		$errorno= curl_errno($curl);
		curl_close($curl);
		if ($errorno) {
			return array('curl'=>false,'errorno'=>$errorno);
		}else{
			$res = json_decode($output,1);

			if ($res['errcode']){
				return array('errcode'=>$res['errcode'],'errmsg'=>$res['errmsg']);
			}else{
				return $res;
			}
		}
		
	
		
// 		$curl = curl_init();
// 		$header = "Accept-Charset: utf-8";
// 		curl_setopt($curl, CURLOPT_URL, $url);
// 		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
// 		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
// 		curl_setopt($curl, CURLOPT_SSLVERSION, 3);
// 		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
// 		if (!empty($data)){
// 			curl_setopt($curl, CURLOPT_POST, 1);
// 			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
// 		}
// 		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
// 		$output = curl_exec($curl);
// 		$errorno= curl_errno($curl);
// 		if ($errorno) {
// 			return array('curl'=>false,'errorno'=>$errorno);
// 		}else{
// 			$res = json_decode($output,1);

// 			if ($res['errcode']){
// 				return array('errcode'=>$res['errcode'],'errmsg'=>$res['errmsg']);
// 			}else{
// 				return $res;
// 			}
// 		}
// 		curl_close($curl);
	}
}

?>