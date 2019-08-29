<?php
class ImageModel extends Model
{
	/**
	 * @param int $oid 用户ID
	 * @param string $tablename 表名
	 * @param number $otype 用户类型 0：系统后台管理员，1：商家后台管理员，2：用户，3：社区后台管理员
	 * @param number $size 图片最大尺寸
	 * @param string $ismark 是否打水印
	 * @return multitype:number string |multitype:number
	 */
// 	public function handle($oid, $tablename, $otype = 0, $size = 5, $ismark = true)
	public function handle($oid, $path, $otype = 0, $param = array('size' => 5), $ismark = true)
	{
// 		array('size' => 5, 'path', 'thumbMaxWidth', 'thumbMaxHeight', 'thumb' => true, 'imageClassPath' => 'ORG.Util.Image', 'thumbPrefix' => 'm_,s_', 'thumbRemoveOrigin' => false);
		
		import("ORG.Net.UploadFile");
		$upload = new UploadFile();
		$upload->maxSize = $param['size'] * 1024 * 1024 *1024;
		$upload->allowExts = array('jpg', 'jpeg', 'png', 'gif', 'mp3');
		$upload->allowTypes = array('image/png', 'image/jpg', 'image/jpeg', 'image/gif', 'audio/mp3');
		
		isset($param['thumb']) && $upload->thumb = $param['thumb'];
		isset($param['imageClassPath']) && $upload->imageClassPath = $param['imageClassPath'];
		isset($param['thumbPrefix']) && $upload->thumbPrefix = $param['thumbPrefix'];
		isset($param['thumbMaxWidth']) && $upload->thumbMaxWidth = $param['thumbMaxWidth'];
		isset($param['thumbMaxHeight']) && $upload->thumbMaxHeight = $param['thumbMaxHeight'];
		isset($param['thumbRemoveOrigin']) && $upload->thumbRemoveOrigin = $param['thumbRemoveOrigin'];
		

		$img_mer_id = sprintf("%09d", $oid);
		$rand_num = substr($img_mer_id, 0, 3) . '/' . substr($img_mer_id, 3, 3) . '/' . substr($img_mer_id, 6, 3);
		
		$upload_dir = "./upload/{$path}/{$rand_num}/";
		if(!is_dir($upload_dir)){
			mkdir($upload_dir, 0777, true);
		}
		
		$upload->savePath = $upload_dir;// 设置附件上传目录
		
		if (!$upload->upload()) {// 上传错误提示错误信息
			return array('error' => 1, 'msg' => $upload->getErrorMsg());
		} else {// 上传成功 获取上传文件信息
			$watermarkfile =  C('config.site_water_mark');//'./upload/watermark/home.png';
			$flag = false;
			if ($ismark && $watermarkfile) {
				$pt = pathinfo($watermarkfile);
				$pu = parse_url($watermarkfile);
				if (isset($pu['path']) && isset($pt['extension'])) {
					$watermarkfile = '.' . $pu['path'];
					$watermarksize = @getimagesize($watermarkfile);
					$watermark = array();
					$watermark['watermarkstatus'] = C('config.site_water_mark_pos');
					$watermark['watermarktype'] = isset($pt['extension']) ? $pt['extension'] : 'png';//'png';
					$watermark['watermarkfile'] = $watermarkfile;
					$watermark['watermarkminwidth'] = $watermarksize[0];
					$watermark['watermarkminheight'] = $watermarksize[1];
					$watermark['watermarkquality'] = 90;
					$watermark['watermarktrans'] = 100;
					$image_water_mark = new image_water_mark();
					$flag = true;
				}
			}
			$images = array();
			$files = $upload->getUploadFileInfo();
			foreach ($files as $file) {
				$images['url'][$file['key']] = substr($file['savepath'] . $file['savename'], 1);
				$images['title'][$file['key']] = $rand_num . ',' . $file['savename'];
				$this->add(array('oid' => $oid, 'otype' => $otype, 'ip' => get_client_ip(), 'dateline' => time(), 'pic' => $images['url'][$file['key']], 'pic_md5' => md5($images['url'][$file['key']])));
				$flag && $image_water_mark->Watermark($file['savepath'] . $file['savename'], $watermark);
			}
			
			$images['error'] = 0;
			return $images;
			
		}
	}
	
	/**
	 * @param int $tableid 表中的主键
	 * @param string $path 图片地址
	 * @param string $tablename 表名
	 * 
	 * path = '/upload/...'
	 */
	public function update_table_id($path, $tableid, $tableName)
	{
		if ($image = $this->field(true)->where(array('pic_md5' => md5($path)))->find()) {
			$this->where(array('pigcms_id' => $image['pigcms_id']))->save(array('tableid' => $tableid, 'tablename' => $tablename));
		}
	}
}