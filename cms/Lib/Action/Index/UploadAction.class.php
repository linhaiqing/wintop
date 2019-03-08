<?php
/*
 * 图片上传
 *
 */

class UploadAction extends BaseAction{
    public function editor_ajax_upload(){
		if(!in_array($_GET['upload_dir'],array('group/content','merchant/news','activity/content','system/image','activity/index_pic','appoint/content'))){			
			$this->editor_alert('非法的目录！');
		}
		
		if($_FILES['imgFile']['error'] != 4){
			$uid = $_SESSION['merchant']['mer_id'] ? $_SESSION['merchant']['mer_id'] : ($_SESSION['system']['mer_id'] ? $_SESSION['system']['mer_id'] : mt_rand(10000,99999));
			$param = array('size' => $this->config['group_pic_size']);
			$image = D('Image')->handle($uid, $_GET['upload_dir'], 0, $param);
			if (!$image['error']) {
				exit(json_encode(array('error' => 0, 'url' => $image['url']['imgFile'])));
			} else {
				$this->editor_alert($image['msg']);
			}
			
			

// 			if(!is_dir($upload_dir)){
// 				mkdir($upload_dir,0777,true);
// 			}
// 			import('ORG.Net.Upload File');
// 			$upload = new Upload File();
// 			// $upload->maxSize = $this->config['group_pic_size']*1024*1024;
// 			$upload->allowExts = array('jpg','jpeg','png','gif');
// 			$upload->allowTypes = array('image/png','image/jpg','image/jpeg','image/gif');
// 			$upload->savePath = $upload_dir; 
// 			$upload->thumb=false;
// 			$upload->saveRule = 'uniqid';
// 			if($upload->upload()){
// 				$uploadList = $upload->getUpload FileInfo();
// 				$url = $upload_dir.$uploadList[0]['savename'];
// 				exit(json_encode(array('error' => 0, 'url' => $url)));
// 			}else{
// 				$this->editor_alert($upload->getErrorMsg());
// 			}
		}else{
			$this->editor_alert('没有选择图片！');
		}
    }
}