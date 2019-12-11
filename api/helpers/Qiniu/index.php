<?php
/**
 * 上传本地文件，需要图片的路径
 */
require 'autoload.php';
// 引入鉴权类
use Qiniu\Auth;
// 引入上传类
use Qiniu\Storage\UploadManager;
// 需要填写你的 Access Key 和 Secret Key
$accessKey = isset($_REQUEST['accessKey'])?$_REQUEST['accessKey']:"B2IicTsGjsbXd_5PRsL6PvazCAyO15ttYlaxC20B";
$secretKey = isset($_REQUEST['secretKey'])?$_REQUEST['secretKey']:"roWvbxyP9KSwxp2m4WkF9jb8Khbam6VpPWO5yw2z";
$bucket = isset($_REQUEST['bucket'])?$_REQUEST['bucket']:"allergan";

if(isset($_REQUEST['files'])){
	$files=json_decode($_REQUEST['files'],true);
	// 构建鉴权对象
	$auth = new Auth($accessKey, $secretKey);
	// 生成上传 Token
	$token = $auth->uploadToken($bucket);
	foreach ($files as $key => $value) {
		// 要上传文件的本地路径
		$filepath = $value['filepath'];
		// 上传到七牛后保存的文件名
		$newname = $value['newname'];
		// 初始化 UploadManager 对象并进行文件的上传。
		$uploadMgr = new UploadManager();
		// 调用 UploadManager 的 putFile 方法进行文件的上传。
		list($ret, $err) = $uploadMgr->putFile($token, $newname, $filepath);	
		if ($err !== null) {
			$files[$key]['error'] = 400;
			$files[$key]['message'] = $err;
		} else {
			$files[$key]['error'] = 200;
			$files[$key]['message'] = '上传成功';
		}
	}
	echo json_encode($files);exit();
}else{
	echo json_encode(array('error'=>400,'message'=>'未上传数据'));
	exit();
}
