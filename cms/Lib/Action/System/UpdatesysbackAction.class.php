<?php

/*
 * 系统升级后台
 *
 */

class UpdatesysbackAction extends CommonAction{
	public $errorFileList = array();
	public $updateFileList = array();
	public $errorUpdateFileList = array();
	public $sqlUpdateFile = array();
	public $sqlError = array();
	public function index(){
		@set_time_limit(0);
		if($_POST['updateKey'] != $this->config['system_key']){
			$this->json_tips(array('error_code'=>10001,'error_msg'=>'密钥检测通过失败'));
		}
		if(!is_writable('./runtime/')){
			$this->json_tips(array('error_code'=>10002,'error_msg'=>'runtime文件夹不可写入'));
		}
		$upload_dir = "./runtime/systemUpdate/";
		if(!is_dir($upload_dir)){
			if(!mkdir($upload_dir,0777,true)){
				$this->json_tips(array('error_code'=>10003,'error_msg'=>'runtime/systemUpdate文件夹不可写入'));
			}
			file_put_contents($upload_dir.'index.html','deny access!');
		}elseif(!is_writable('./runtime/systemUpdate/')){
			$this->json_tips(array('error_code'=>10004,'error_msg'=>'runtime/systemUpdate文件夹不可写入'));
		}
		
		import('ORG.Net.UploadFile');
		$upload = new UploadFile();
		$upload->maxSize = 100*1024*1024;
		$upload->savePath = $upload_dir;
		$upload->saveRule = 'uniqid';
		if(!$upload->upload()){
			$this->json_tips(array('error_code'=>10005,'error_msg'=>'文件上传失败！错误提示：'.$upload->getErrorMsg()));
		}
		
		$uploadList = $upload->getUploadFileInfo();				
		$localZipName = './runtime/systemUpdate/'.$uploadList[0]['savename'];
		
		$cacheUpdateFileName = uniqid();
		$cacheUpdateDirName = './runtime/systemUpdate/'.$cacheUpdateFileName;
		$rightUpdateDirName = 'runtime'.DIRECTORY_SEPARATOR.'systemUpdate'.DIRECTORY_SEPARATOR.$cacheUpdateFileName.DIRECTORY_SEPARATOR;
		import('@.ORG.PclZip');
		$archive = new PclZip($localZipName);
		if($archive->extract(PCLZIP_OPT_PATH, $cacheUpdateDirName, PCLZIP_OPT_REPLACE_NEWER) == 0){
			$this->json_tips(array('error_code'=>10006,'error_msg'=>'文件解压失败！错误提示：'.$archive->errorInfo(true)));
		}
		$this->checkWritable($cacheUpdateDirName,$rightUpdateDirName);
		
		if(!empty($this->errorFileList)){
			$this->json_tips(array('error_code'=>10007,'error_msg'=>'文件权限检测通过失败！','fileList'=>$this->errorFileList));
		}
		foreach($this->updateFileList as $value){
			if($value['fileType'] == 'dir'){
				if(!file_exists($value['updateName']) && !mkdir($value['updateName'],0777,true)){
					$this->json_tips(array('error_code'=>10008,'error_msg'=>'文件夹创建失败！文件名：'.$value['updateName']));
				}
			}else if($value['fileType'] == 'file'){
				if(!copy($value['cacheName'],$value['updateName'])){
					$this->json_tips(array('error_code'=>10009,'error_msg'=>'文件复制失败！文件名：'.$value['updateName']));
				}
			}
		}
		//处理sql文件
		if($this->sqlUpdateFile){
			foreach($this->sqlUpdateFile as $value){
				$sql = file_get_contents($value);
				if(!empty($sql)){
					$sqls = explode(';',$sql);
					foreach($sqls as $value){
						$value = trim($value);
						if(!empty($value)){
							if(D('')->execute($value) === false){
								array_push($this->sqlError,$value);
							}
						}
					}
				}
			}
			if($this->sqlError){
				$this->json_tips(array('error_code'=>10010,'error_msg'=>'数据库升级出现问题！','sqlList'=>$this->sqlError));
			}
		}
		D('Config')->where(array('name' => 'system_version'))->data(array('value' => $_POST['updateVersion']))->save();
		$this->json_tips(array('error_code'=>0,'error_msg'=>'升级完成'));
	}
	protected function json_tips($array){
		header('error_code: '.$array['error_code']);
		$this->rmCache();
		echo json_encode($array);exit();
	}
	//检测需更新文件权限
	protected function checkWritable($cacheUpdateDirName,$rightUpdateDirName){		
		$file_list = $this->getDirList($cacheUpdateDirName);
		foreach($file_list as $value){
			$updateFileName = str_replace($rightUpdateDirName,'',$value['pathname']);
			if($value['ext'] == 'sql'){
				array_push($this->sqlUpdateFile,$value['pathname']);
				continue;
			}
			array_push($this->updateFileList,array('fileType'=>$value['type'],'cacheName'=>$value['pathname'],'updateName'=>$updateFileName));
			if(file_exists($updateFileName) && !is_writable($updateFileName)) array_push($this->errorFileList,$updateFileName);
			if($value['isDir']) $this->checkWritable($cacheUpdateDirName.DIRECTORY_SEPARATOR.$value['filename'],$rightUpdateDirName);
		}
	}
	protected function getDirList($path){
		import('ORG.Util.Dir');
		$DirClass = new Dir($path);
		return $DirClass->_values;
	}
	protected function rmCache(){
		import('ORG.Util.Dir');
        Dir::delDirnotself('./runtime/');
	}
	public function workorder_check(){
		echo 'ok';
	}
	public function workorder_code(){
		if($_POST['workorder_key'] != $this->config['workorder_key']){
			exit('key错误');
		}else{
			exit('ok');
		}
	}
}

?>