<?php
namespace App\Api;

use PhalApi\Api;

/**
 * 微信端接口
 */
class DoubleEleven extends Api {

 	public function __construct(){

    }

    /**
     * 调用微信接口获取签名
     */
    public function getJsApiTicket($accessToken) {
        $ticket = \PhalApi\DI()->cache->get('ticket');
        if(!$ticket){
            $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=$accessToken&type=jsapi";
            // 微信返回的信息
            $ticket = json_decode($this->http_get_data($url));
            if($ticket){
                \PhalApi\DI()->cache->set('ticket', $ticket->ticket, 3600);
            }
        }
        return $ticket;
    }

    /**
     * 获取签名接口
     */
    public function get_ticket() {
        $params = \PhalApi\DI()->request->getAll();
        // 获取token
        $token = $this->getAccessToken();
        // 获取ticket
        $ticket = $this->getJsApiTicket($token);
        
        // 该url为调用jssdk接口的url
        $url = isset($params['url'])?$params['url']:'';
        // 生成时间戳
        $timestamp = time();
        // 生成随机字符串
        $nonceStr = $this->createNoncestr();
        // 这里参数的顺序要按照 key 值 ASCII 码升序排序 j -> n -> t -> u
        $string = "jsapi_ticket=$ticket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
        $signature = sha1($string);
        $signPackage = array (
            "appId" => \PhalApi\DI()->config->get('app.weixin.appid'),
            "nonceStr" => $nonceStr,
            "timestamp" => $timestamp,
            "url" => $url,
            "signature" => $signature,
            "rawString" => $string,
            "ticket" => $ticket,
            "token" => $token
        );
 
        // 返回数据给前端
        return array('res' => $signPackage, 'msg' => '');
    }
 
    /**
     * 创建随机字符串
     */
    public function createNoncestr($length = 16) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for($i = 0; $i < $length; $i ++) {
            $str .= substr ( $chars, mt_rand ( 0, strlen ( $chars ) - 1 ), 1 );
        }
        return $str;
    }

    /**
     * 获取微信access_token
     */
    public function getAccessToken(){
        # 获取缓存中的access_token
        $access_token = \PhalApi\DI()->cache->get('access_token');
        if(!$access_token){
            $access_token = $this->getTokenapi();
            if($access_token){
                \PhalApi\DI()->cache->set('access_token', $access_token->access_token, 3600);
            }
        }
        return $access_token;
    }
    
    /**
     * 获取access_token接口
     */
    public function getTokenapi(){
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . \PhalApi\DI()->config->get('app.weixin.appid') . "&secret=" . \PhalApi\DI()->config->get('app.weixin.secret');
        $token = $this->http_get_data($url);
        $token = json_decode(stripslashes($token));
        $arr = json_decode(json_encode($token), true);
        $access_token = $arr['access_token'];
        return $token;
    }

    /**
     * 发送自定义的模板消息
     * @param $touser
     * @param $template_id
     * @param $url
     * @param $data
     * @param string $topcolor
     * @return bool
     */
    public function doSend($touser, $template_id, $url = '', $data, $type=0, $topcolor = '#173177'){
        $template = array(
            'touser' => $touser,
            'template_id' => $template_id,
            'color' => $topcolor,
            'data' => $data
        );
        if($type == 0){
            $template['url'] = $url;
        }else if($type == 1){
            $template['miniprogram'] = $url;
        }
        $json_template = json_encode($template);
        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . \PhalApi\DI()->cache->get('access_token');
        return $this->http_post_data($url, urldecode($json_template));
    }

    /**
     * 通过code换取token
     */
    public function GetToken(){
        try{
            $params = \PhalApi\DI()->request->getAll();
            $code = isset($params['code'])?$params['code']:'';
            if(empty($code)){
                return array('res' => '', 'msg' => '未找到code值');
            }
            # 获取token和openid
            $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".\PhalApi\DI()->config->get('app.weixin.appid')."&secret=".\PhalApi\DI()->config->get('app.weixin.secret')."&code=$code&grant_type=authorization_code";
            $res = json_decode($this->http_get_data($url));
            $token = $res->access_token;
            $openid = $res->openid;
            # 获取用户信息
            $url = "https://api.weixin.qq.com/sns/userinfo?access_token=$token&openid=$openid&lang=zh_CN";
            $res = json_decode($this->http_get_data($url));
            return array('res' => $res, 'msg' => '');
        } catch (\Exception $e) {
            return array('res' => $e->getCode() , 'msg' => '');
        }
    }

    /**
     * CURL_GET方法
     */
    public function http_get_data($url) {
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
