<?php
/*
 * 图片处理控制器
 *
 */
class ImageAction extends BaseAction{
	public $thumbWidthHeight = array(
		'276_168',
		'228_140',
		'198_120',
		'120_120',
	);
    public function thumb(){
		// header('HTTP/1.1 304 Not Modified');
		$url = $_GET['url'];
		$width = intval($_GET['width']);
		$height = intval($_GET['height']);
		if(empty($url) || empty($width) || empty($height) || !in_array($width.'_'.$height,$this->thumbWidthHeight)){
			redirect($url);
		}
		// echo $this->config['site_url'].'/upload/';exit;
		if(strpos($url,$this->config['site_url'].'/upload/') === 0){
			$filePath = str_replace($this->config['site_url'].'/upload/','',$url);
			$filePathInfo = pathinfo($filePath);
			$thumbFile = './upload/thumbImg/'.$width.'_'.$height.'/'.$filePath;
			$thumbFilePathInfo =  pathinfo($thumbFile);
			
			switch($thumbFilePathInfo['extension']){
				case 'png':
					$headerType = 'image/png';
					break;
				case 'gif':
					$headerType = 'image/gif';
					break;
				default:
					$headerType = 'image/jpeg';
			}
			if(file_exists($thumbFile)){
				$this->_addEtag($thumbFile);
				header('Content-type: '.$headerType);
				echo file_get_contents($thumbFile);exit;
			}
			
			
			if(!file_exists($thumbFilePathInfo['dirname'].'/') && !mkdir($thumbFilePathInfo['dirname'].'/',0777,true)){
				redirect($url);
			}
			import('ORG.Util.Image');
			$ImageObj = new Image();
			if($ImageObj->thumb('./upload/'.$filePath,$thumbFile,'',$width,$height)){
				$this->_addEtag($thumbFile);
				header('Content-type: '.$headerType);
				echo file_get_contents($thumbFile);exit;
			}else{
				redirect($url);
			}
		}else{
			redirect($url);
		}
    }
	private function _addEtag($file){
		header("Cache-Control: private, max-age=10800, pre-check=10800");
header("Pragma: private");
header("Expires: " . date(DATE_RFC822,strtotime(" 2 day")));
	}
}