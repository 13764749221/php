<?php
namespace App\Api;

use PhalApi\Api;
header('Access-Control-Allow-Origin:*');
/**
 * 用户模块接口服务
 */
class WebApi extends Api {

 	public function __construct(){
        $this->QuestionModel = new \App\Model\Web\Question();
        $this->AddressModel = new \App\Model\Web\Address();
    }

    /**
     * 保存问卷数据
     * @return [type] [description]
     */
    public function SaveQuestion(){
    	try{
			$params = \PhalApi\DI()->request->getAll();
			unset($params['s']);
			$params['created_at'] = date("Y-m-d H:i:s");
	        $res = $this->QuestionModel->insert($params);
	        return array('res' => $res, 'msg' => "");
        } catch (\Exception $e) {
        	return array('res' => $e->getCode() , 'msg' => $e->getMessage());
        }
    }

    /**
     * 保存地址信息
     * @return [type] [description]
     */
    public function SaveAddress(){
    	try{
			$params = \PhalApi\DI()->request->getAll();
			unset($params['s']);
			$params['created_at'] = date("Y-m-d H:i:s");
	        $res = $this->AddressModel->insert($params);
	        return array('res' => $res, 'msg' => "");
        } catch (\Exception $e) {
        	return array('res' => $e->getCode() , 'msg' => $e->getMessage());
        }
    }

    /**
     * 获取access_token
     */
    public function getTokenapi(){
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . \PhalApi\DI()->config->get('app.weixin.appid') . "&secret=" . \PhalApi\DI()->config->get('app.weixin.secret');
        $token = $this->http_get_data($url);
        \PhalApi\DI()->logger->info('token', $token);
        $token = json_decode(stripslashes($token));
        return $token;
    }

    //获取jsapi_ticket
	public function getJsapi(){
        $accessToken = $this->getTokenapi();
        $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=".$accessToken['access_token'];
        return $url;
		$result = $this->http_get_data($url);
		//生成文件，保存token
		$dir = __DIR__; //真实路径，crontab命令的php执行在cli模式下，不能正确识别相对路径，所以使用__DIR__
		$filename = $dir."/jsapi_ticket.php";
        create_file($filename, $result);
        return $filename;
    }

    /**
     *
     * curl(GET)
     *
     * @return object list
     */
    public function http_get_data($url){       
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_URL, $url);
        $res = curl_exec($curl);
        curl_close($curl);
        return $res;
    }

    /**
     *
     * curl(POST)
     *
     * @return object list
     */
    public function http_post_data($url,$post_data){       
        //初始化
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, false);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        //设置post方式提交
        curl_setopt($curl, CURLOPT_POST, true);
        // 设置post请求参数
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
        // 添加头信息
        // curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        // CURLINFO_HEADER_OUT选项可以拿到请求头信息
        curl_setopt($curl, CURLINFO_HEADER_OUT, true);
        // 不验证SSL
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        //执行命令
        $data = curl_exec($curl);
        // 打印请求头信息
        // echo curl_getinfo($curl, CURLINFO_HEADER_OUT);
        //关闭URL请求
        curl_close($curl);
        //显示获得的数据
        return $data;
    }
} 
