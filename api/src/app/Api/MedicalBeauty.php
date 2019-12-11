<?php
namespace App\Api;

use PhalApi\Api;

header('Access-Control-Allow-Origin:*');
/**
 * 用户模块接口服务
 */
class MedicalBeauty extends Api {

 	public function __construct(){
	   $this->QiNiuModel = new \Qiniu();// 实例化七牛方法
	   $this->Qrcode = new \App\Model\Assistant\Qrcode();
    }

    /**
     * 获取客户列表
     * @return [type] [description]
     */
    public function Customerlist(){
    	try{
	        $params = \PhalApi\DI()->request->getAll();
	        $page = isset($params['page'])?$params['page']:1;
	        $page_size = isset($params['page_size'])?$params['page_size']:10;
	        $name = isset($params['name'])?$params['name']:"";
	        $where = array();
	        $start = ($page-1)*$page_size;
	        $end = $page*$page_size;
	        if($name){
	            $where['name = ?'] = $name;
	        }
	        $res = $this->Qrcode->getall($where,$start,$end,'id DESC');
	        $count = $this->Qrcode->getcount($where);
	        return array('res' => $res, 'count' => intval($count));
        } catch (\Exception $e) {
        	return array('res' => $e->getCode() , 'msg' => $e->getMessage());
        }
    }
} 
