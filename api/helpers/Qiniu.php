<?php  
header("Content-Type:text/html;charset=utf-8");

/*
|--------------------------------------------------------------------------
| 七牛调用公用类
|--------------------------------------------------------------------------
|
| @author HarveyCheng <harvey.cheng@eosi.com.cn>
|
*/
require_once dirname(__FILE__).'/Qiniu/autoload.php';
// 引入鉴权类
use Qiniu\Auth;
// 引入上传类
use Qiniu\Storage\UploadManager;

class Qiniu
{

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->accessKey = \PhalApi\DI()->config->get('app.qiniu.accessKey');
        $this->secretKey = \PhalApi\DI()->config->get('app.qiniu.secretKey');
        $this->bucket = \PhalApi\DI()->config->get('app.qiniu.bucket');
    }

    /**
     * 公共上传文件接口
     * 
     * @param  array  $files  array(0=>array('filepath'=>'./storage/uploads/201612022024341461.jpg','newname'=>'newimgas.png')
     * @return [type] [code:返回码,message,返回信息]
     */
    public function uploadfiles($files = array()){
        $accessKey = $this->accessKey;
        $secretKey = $this->secretKey;
        $bucket = $this->bucket;
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
                $files[$key]['code'] = 400;
                $files[$key]['message'] = $err;
            } else {
                $files[$key]['code'] = 200;
                $files[$key]['message'] = '上传成功';
            }
        }
        return $files;
    }

    /**
     * 公共删除文件接口
     * 
     * @param  array  $files array(0=>array('filename'=>'./storage/uploads/201612022024341461.jpg')
     * @return [type]        [code:返回码,message,返回信息]
     */
    public function deletefiles($files = array()){
        $accessKey = $this->accessKey;
        $secretKey = $this->secretKey;
        $bucket = $this->bucket;
        $auth = new Auth($accessKey, $secretKey);
        $config = new \Qiniu\Config();
        foreach ($files as $key => $value) {
            $key = $value['filename'];
            $bucketManager = new \Qiniu\Storage\BucketManager($auth, $config);
            $err = $bucketManager->delete($bucket, $key);
            if ($err !== null) {
                $files[$key]['code'] = 400;
                $files[$key]['message'] = $err;
            } else {
                $files[$key]['code'] = 200;
                $files[$key]['message'] = '删除成功';
            }
        }
        return $files;
    }

    /**
     * 发送短信信息
     * @param  array  $data [description] template_id:模板id，phone：手机号，content：发送变量内容
     * $data = array(
     *           'template_id' => '1156043286817804288',
     *          'phone' => array(
     *               '13764749221',
     *               '17604567271',
     *         ),
     *          'content' => array(
     *              'shop' => '手机卡',
     *              'money' => '100.01',
     *         ),
     *     );
     * @return [type]       [description]
     */
    public function sms($data = array()){
        $accessKey = $this->accessKey;
        $secretKey = $this->secretKey;
        $auth = new Auth($accessKey, $secretKey);
        $client = new Qiniu\Sms\Sms($auth);
        //发送信息模块
        $template_id = $data['template_id'];
        $mobiles = $data['phone'];
        $code = $data['content'];
        try {
            //发送短信
            $resp = $client->sendMessage($template_id, $mobiles, $code);
            return array('code'=>200,'message'=>$resp);
        } catch (\Exception $e) {
            return array('code'=>400,'message'=>$data);
        }
    }

    /**
     * [selectsign 查询签名]
     * @param  [type] $audit_status [审核状态, 取值范围为: "passed"(通过), "rejected"(未通过), "reviewing"(审核中)]
     * @param  [type] $page         [页码。默认为 1]
     * @param  [type] $page_size    [分页大小。默认为 20]
     * @return [type]               [description]
     */
    public function selectsign($audit_status,$page,$page_size){
        $accessKey = $this->accessKey;
        $secretKey = $this->secretKey;
        $auth = new Auth($accessKey, $secretKey);
        $client = new Qiniu\Sms\Sms($auth);
        try {
            //发送短信
            $resp = $client->checkSignature($audit_status,$page,$page_size);
            return array('code'=>200,'message'=>$resp);
        } catch (\Exception $e) {
            return array('code'=>400,'message'=>$data);
        } 
    }

    /**
     * [selectsign 查询模板]
     * @param  [type] $audit_status [审核状态, 取值范围为: "passed"(通过), "rejected"(未通过), "reviewing"(审核中)]
     * @param  [type] $page         [页码。默认为 1]
     * @param  [type] $page_size    [分页大小。默认为 20]
     * @return [type]               [description]
     */
    public function selectemplate($audit_status,$page,$page_size){
        $accessKey = $this->accessKey;
        $secretKey = $this->secretKey;
        $auth = new Auth($accessKey, $secretKey);
        $client = new Qiniu\Sms\Sms($auth);
        try {
            //发送短信
            $resp = $client->queryTemplate($audit_status,$page,$page_size);
            return array('code'=>200,'message'=>$resp);
        } catch (\Exception $e) {
            return array('code'=>400,'message'=>$data);
        } 
    }

     /**
      * 短信发送状态查询接口
      * @param  [type] $job_id      [发送任务返回的 id]
      * @param  [type] $mobile      [接收短信的手机号码]
      * @param  [type] $template_id [模版 id]
      * @param  [type] $start       [开始时间，timestamp，例如: 1563280448]
      * @param  [type] $end         [结束时间，timestamp，例如: 1563280471]
      * @param  [type] $page        [页码，默认为 1]
      * @param  [type] $page_size   [每页返回的数据条数，默认20，最大200]
      * @return [type]              [description]
      */
    public function getsmshistroy($job_id,$mobile,$template_id,$start,$end,$page,$page_size){
        $accessKey = $this->accessKey;
        $secretKey = $this->secretKey;
        $auth = new Auth($accessKey, $secretKey);
        $client = new Qiniu\Sms\Sms($auth);
        try {
            //发送短信
            $resp = $client->querysendMessage($job_id,$mobile,$template_id,$start,$end,$page,$page_size);
            return array('code'=>200,'message'=>$resp);
        } catch (\Exception $e) {
            return array('code'=>400,'message'=>$data);
        }
    }

    /**
     * 二进制流文件上传接口（多个）
     * 
     * @param  array  $files  array(0=>array('fileinfo'=>'hello word','newname'=>'formput')
     * @return [type] [code:返回码,message,返回信息]
     */
    public function uploadputs($files = array()){
        $accessKey = $this->accessKey;
        $secretKey = $this->secretKey;
        $bucket = $this->bucket;
        $auth = new Auth($accessKey, $secretKey);
        // 生成上传 Token
        $token = $auth->uploadToken($bucket);
        foreach ($files as $key => $value) {
            // 要上传文件的本地路径
            $filepath = $value['fileinfo'];
            // 上传到七牛后保存的文件名
            $newname = $value['newname'];
            // 初始化 UploadManager 对象并进行文件的上传。
            $uploadMgr = new UploadManager();
            // 调用 UploadManager 的 putFile 方法进行文件的上传。
            list($ret, $err) = $uploadMgr->put($token, $newname, $value['fileinfo']);    
            if ($err !== null) {
                $files[$key]['code'] = 400;
                $files[$key]['message'] = $err;
            } else {
                $files[$key]['code'] = 200;
                $files[$key]['message'] = '上传成功';
            }
            // 删除二进制流文件
            unset($files[$key]['fileinfo']);
        }
        return $files;
    }

    /**
     * 二进制流文件上传接口（单个）
     * 
     * @param  array  $files  array(0=>array('fileinfo'=>'hello word','newname'=>'formput')
     * @return [type] [code:返回码,message,返回信息]
     */
    public function uploadput($files,$filename,$space = ''){
        $accessKey = $this->accessKey;
        $secretKey = $this->secretKey;
        $bucket = $space?$space:$this->bucket;
        $auth = new Auth($accessKey, $secretKey);
        // 生成上传 Token
        $token = $auth->uploadToken($bucket);
        // 初始化 UploadManager 对象并进行文件的上传。
        $uploadMgr = new UploadManager();
        // 调用 UploadManager 的 putFile 方法进行文件的上传。
        list($ret, $err) = $uploadMgr->put($token, $filename, $files);    
        if ($err !== null) {
            $data['code'] = 400;
            $data['message'] = $err;
        } else {
            $data['code'] = 200;
            $data['message'] = '上传成功';
        }
        return $data;
    }
}
