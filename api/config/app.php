<?php
/**
 * 请在下面放置任何您需要的应用配置
 *
 * @license     http://www.phalapi.net/license GPL 协议
 * @link        http://www.phalapi.net/
 * @author dogstar <chanzonghuang@gmail.com> 2017-07-13
 */

return array(

    /**
     * 应用接口层的统一参数
     */
    'apiCommonRules' => array(
        //'sign' => array('name' => 'sign', 'require' => true),
    ),

    /**
     * 接口服务白名单，格式：接口服务类名.接口服务方法名
     *
     * 示例：
     * - *.*         通配，全部接口服务，慎用！
     * - Site.*      Api_Default接口类的全部方法
     * - *.Index     全部接口类的Index方法
     * - Site.Index  指定某个接口服务，即Api_Default::Index()
     */
    'service_whitelist' => array(
        'Site.Index',
    ),
    /**
     * 七牛云配置文件
     */
    'qiniu' => array(
        'accessKey' => 'B2IicTsGjsbXd_5PRsL6PvazCAyO15ttYlaxC20B',
        'secretKey' => 'roWvbxyP9KSwxp2m4WkF9jb8Khbam6VpPWO5yw2z',
        'bucket' => 'allergan',
        'upload_url' => 'http://sms.eoiyun.com/api/public/?s=QiNiuYun.uploadputfiles',//七牛上传文件接口路径
        'sendsms_url' => 'http://sms.eoiyun.com/api/public/?s=QiNiuYun.sendsms',//七牛发送短信接口路径
        'signcode' => '1173437105485914112',//验证码模板配置
    ),
    /**
     * 本地文件存储配置
     */
    'upload' => array(
        'path' => '/yimeikefu/api/upload/'//本地文件存储路径
    ),
    /**
     * 公众号相关配置
     */
    'weixin' => array(
        'appid' => 'wx94ec8a32fa6de6f1',//公众号appid
        'secret' => '0fc43783297c59d1a0f59fd96c02f18e',//公众号secret
    ),
    /**
     * 小程序相关配置
     */
    'miniapp' => array(
        'appid' => 'wx94608d1796d26625',// 小程序appid
    ),
);
