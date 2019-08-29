<?php

/*
 * 系统升级
 *
 */

class UpdatesysAction extends BaseAction {
	public function index(){
		$system_key = md5(uniqid().$_SERVER['REQUEST_TIME']);
		if(!isset($this->config['system_key'])){
			if(!D('Config')->add(array('name' => 'system_key', 'value' => $system_key, 'gid' => 0, 'status' => 1))){
				$this->frame_main_ok_tips('处理失败，请重试。',3,U('Index/main'));
			}
		}else if(!D('Config')->where(array('name' => 'system_key'))->data(array('value' => $system_key))->save()){
			$this->frame_main_ok_tips('处理失败，请重试。');
		}
		$array = parse_url($this->config['site_url']);
		$data = array(
			'domain' => $array['host'],
			'key' 	 => $system_key,
			'soft_version' => $this->config['system_version'],
		);
		import('ORG.Net.Http');
		$http = new Http();	
		$returnArr = Http::curlPost('http://o2o-service.linhaiqing.com/update.php', $data);
		if(isset($returnArr['error_code'])){
			if($returnArr['error_code']){
				$this->frame_main_ok_tips($returnArr['error_msg'],10,U('Index/main'));
			}else{
				$this->frame_main_ok_tips($returnArr['error_msg'], 10, U('Index/main'));
			}
		}else{
			$this->frame_main_ok_tips('升级发生异常！请联系售后进行协助解决',3,U('Index/main'));
		}
	}
	public function update(){
		if($_POST['updateKey'] != $this->config['system_key']){
			echo json_encode(array('error_code'=>10001,'error_msg'=>'密钥检测通过失败'));exit();	
		}
		if(!is_writable('./runtime/')){
			echo json_encode(array('error_code'=>10002,'error_msg'=>'runtime文件夹不可写入'));exit();	
		}
		$upload_dir = "./runtime/systemUpdate/";
		if(!is_dir($upload_dir)){
			if(!mkdir($upload_dir,0777,true)){
				echo json_encode(array('error_code'=>10003,'error_msg'=>'runtime/systemUpdate文件夹不可写入'));exit();	
			}
			file_put_contents($upload_dir.'index.html','deny access!');
		}elseif(!is_writable('./runtime/systemUpdate/')){
			echo json_encode(array('error_code'=>10004,'error_msg'=>'runtime/systemUpdate文件夹不可写入'));exit();	
		}
		
		import('ORG.Net.UploadFile');
		$upload = new UploadFile();
		$upload->maxSize = 100*1024*1024;
		$upload->savePath = $upload_dir;
		$upload->saveRule = 'uniqid';
		if(!$upload->upload()){
			echo json_encode(array('error_code'=>10005,'error_msg'=>'文件上传失败！错误提示：'.$upload->getErrorMsg()));exit();				
		}
		echo '1111';exit;
		
		$uploadList = $upload->getUploadFileInfo();				
		$title = $rand_num.','.$uploadList[0]['savename'];
		exit(json_encode(array('error' => 0,'url' =>'./upload/files/'.$rand_num.'/'.$uploadList[0]['savename'],'title'=>$title)));
	}
	public function workorder(){
		$workorder_key = md5(uniqid().$_SERVER['REQUEST_TIME']);
		if(!isset($this->config['workorder_key'])){
			if(!D('Config')->add(array('name' => 'workorder_key', 'value' => $workorder_key, 'gid' => 0, 'status' => 1))){
				$this->error_tips('处理失败，请重试。',3,U('Index/main'));
			}
		}else if(!D('Config')->where(array('name' => 'workorder_key'))->data(array('value' => $workorder_key))->save()){
			$this->error_tips('处理失败，请重试。');
		}
		$array = parse_url($this->config['site_url']);
		$data = array(
			'domain' => $array['host'],
			'key' 	 => $workorder_key,
		);
		import('ORG.Net.Http');
		$http = new Http();	
		$returnArr = Http::curlPost('http://o2o-service.linhaiqing.com/workorder/login.php', $data);
		if(isset($returnArr['error_code'])){
			if($returnArr['error_code']){
				$this->error_tips($returnArr['error_msg'],10,U('Index/main'));
			}else{
				redirect('http://o2o-service.linhaiqing.com/workorder/login.php?workorder_key='.$returnArr['workorder_key'].'&private_key='.$returnArr['private_key']);
			}
		}else{
			$this->error_tips('进入工单发生异常！请联系售后进行协助解决',3,U('Index/main'));
		}
	}
}

?>