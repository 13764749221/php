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
        $this->TicketModel = new \App\Model\Web\Ticket();
        $this->CityModel = new \App\Model\Web\City();
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
        $token = json_decode(stripslashes($token));
        \PhalApi\DI()->logger->info('token_new', $token->access_token);
        
        return $token->access_token;
    }

    //获取jsapi_ticket
	public function getJsapi(){
        $accessToken = $this->getTokenapi();
        $res = $this->TicketModel->select_where();
        if($res){
            if(time() < $res['overtime']){
                \PhalApi\DI()->logger->info('token_old', $res['overtime']);
                return $res['ticket'];
            }
        }
		$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=".$accessToken;
        $result = json_decode($this->http_get_data($url));
        $data = array(
            "ticket" => $result->ticket,
            "overtime" => time() + 6000,
            "created_at" => date("Y-m-d H:i:s")
        );
        $this->TicketModel->insert($data);
        return $result->ticket;
    }

    public function getSignPackage() {
        $params = \PhalApi\DI()->request->getAll();
        $jsapiTicket = $this->getJsapi();
        // 注意 URL 一定要动态获取，不能 hardcode.
        $url = $params['url'];
        $timestamp = time();
        $nonceStr = $this->createNonceStr();
     
        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
     
        $signature = sha1($string);
     
        $signPackage = array(
          "appId"     => \PhalApi\DI()->config->get('app.weixin.appid'), 
          "nonceStr"  => $nonceStr,
          "timestamp" => $timestamp,
          "url"       => $url,
          "signature" => $signature,
          "rawString" => $string
        );
        return $signPackage; 
    }
    
    public function createNonceStr($length = 16) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
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

    public function getcity(){
        $res = $this->CityModel->groupall();
        $array = array();
        foreach ($res as $key => $value) {
            $info = $this->CityModel->getall($value['province_code']);
            $array_d = array(
                "province_code" => $value['province_code'],
                "city" => $info
            );
            $array[] =  $array_d;
        }
        return $array;
    }

} 
