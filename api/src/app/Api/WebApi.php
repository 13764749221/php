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
    }

    /**
     * 获取客户列表
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
} 
