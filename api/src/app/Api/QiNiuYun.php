<?php
namespace App\Api;

use PhalApi\Api;

/**
 * 用户模块接口服务
 */
class QiNiuYun extends Api {

 	public function __construct(){
       $this->QiNiuModel = new \Qiniu();// 实例化七牛方法
       $this->Signmodel = new \App\Model\Yun\Sign();
       $this->Templatemodel = new \App\Model\Yun\Template();
       $this->Smslogmodel = new \App\Model\Yun\Smslog();
       $this->Usermodel = new \App\Model\Yun\User();
       $this->Rechargemodel = new \App\Model\Yun\Recharge();
       $this->UserSignmodel = new \App\Model\Yun\UserSign();
    }

    public function ceshi(){
    	$model = new \App\Model\Yun\Sign();
    	$params = \PhalApi\DI()->request->getAll();
    	$res = $model->get(1);
        return array('is_login' => $res, 'user_id' => '');
    }

    /**
     * [selectsign 查询签名]
     * @param  [type] $audit_status [审核状态, 取值范围为: "passed"(通过), "rejected"(未通过), "reviewing"(审核中)]
     * @param  [type] $page         [页码。默认为 1]
     * @param  [type] $page_size    [分页大小。默认为 20]
     * @return [type]               [description]
     */
    public function selectsign(){
    	$audit_status = null;
    	$page = 1;
    	$page_size = 20;
    	$res = $this->QiNiuModel->selectsign($audit_status,$page,$page_size);
    	$data = array();
    	if(isset($res['message'][0]['items'])){
    		$data = $res['message'][0]['items'];
    	}
    	foreach ($data as $key => $value) {
    		$data_new = array();
    		$data_new['sign_id'] = $value['id'];
    		$data_new['signature'] = $value['signature'];
    		$data_new['source'] = $value['source'];
    		$data_new['audit_status'] = $value['audit_status'];
    		$data_new['reject_reason'] = $value['reject_reason'];
    		$data_new['description'] = $value['description'];
    		$data_new['uid'] = $value['uid'];
            $data_new['created_at'] = date("Y-m-d H:i:s",$value['created_at']);
            $data_new['updated_at'] = date("Y-m-d H:i:s",$value['updated_at']);
    		# 数据是否存在
    		$info = $this->Signmodel->select_signature($value['signature']);
    		if($info){
    			$this->Signmodel->updated_sign($value['signature'],$data_new);
    		}else{
    			$this->Signmodel->insert($data_new);
    		}
    	}
    	return array('res' => $data);
    }

    /**
     * 获取签名列表
     * @return [type] [description]
     */
    public function getsignlist(){
        $params = \PhalApi\DI()->request->getAll();
        $page = isset($params['page'])?$params['page']:1;
        $page_size = isset($params['page_size'])?$params['page_size']:10;
        $signature = isset($params['signature'])?$params['signature']:"";
        $where = array();
        $start = ($page-1)*$page_size;
        $end = $page*$page_size;
        if($signature){
            $where['signature = ?'] = $signature;
        }
        $res = $this->Signmodel->getall($where,$start,$end);
        $count = $this->Signmodel->getcount($where);
        return array('res' => $res, 'count' => intval($count));
    }


    /**
     * [selectsign 查询模板]
     * @param  [type] $audit_status [审核状态, 取值范围为: "passed"(通过), "rejected"(未通过), "reviewing"(审核中)]
     * @param  [type] $page         [页码。默认为 1]
     * @param  [type] $page_size    [分页大小。默认为 20]
     * @return [type]               [description]
     */
    public function selectemplate(){
        $audit_status = null;
        $page = 1;
        $page_size = 100;
        $res = $this->QiNiuModel->selectemplate($audit_status,$page,$page_size);
        $data = array();
        if(isset($res['message'][0]['items'])){
            $data = $res['message'][0]['items'];
        }
        foreach ($data as $key => $value) {
            $data_new = array();
            $data_new['template_id'] = $value['id'];
            $data_new['name'] = $value['name'];
            $data_new['template'] = $value['template'];
            $data_new['audit_status'] = $value['audit_status'];
            $data_new['reject_reason'] = $value['reject_reason'];
            $data_new['type'] = $value['type'];
            $data_new['signature_id'] = $value['signature_id'];
            $data_new['signature_text'] = $value['signature_text'];
            $data_new['description'] = $value['description'];
            $data_new['disabled'] = $value['disabled'];
            $data_new['disable_reason'] = $value['disable_reason'];
            $data_new['uid'] = $value['uid'];
            $data_new['created_at'] = date("Y-m-d H:i:s",$value['created_at']);
            $data_new['updated_at'] = date("Y-m-d H:i:s",$value['updated_at']);
            # 数据是否存在
            $info = $this->Templatemodel->select_template($value['id']);
            if($info){
                $this->Templatemodel->updated_template($value['id'],$data_new);
            }else{
                $this->Templatemodel->insert($data_new);
            }
        }
        return array('res' => $data);
    }

    /**
     * 获取模板列表
     * @return [type] [description]
     */
    public function getemplatelist(){
        $params = \PhalApi\DI()->request->getAll();
        $page = isset($params['page'])?$params['page']:1;
        $page_size = isset($params['page_size'])?$params['page_size']:10;
        $name = isset($params['name'])?$params['name']:"";
        $signvalue = isset($params['signvalue'])?$params['signvalue']:"";
        $where = array();
        $start = ($page-1)*$page_size;
        $end = $page*$page_size;
        if($name){
            $where['name = ?'] = $name;
        }
        if($signvalue){
            $where['signature_id = ?'] = $signvalue;
        }
        $res = $this->Templatemodel->getall($where,$start,$end);
        $count = $this->Templatemodel->getcount($where);
        return array('res' => $res, 'count' => intval($count));
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
    public function gethistory(){
        $res = $this->QiNiuModel->getsmshistroy(null,null,null,null,null,1,20);
        $data = array();
        if(isset($res['message'][0]['items'])){
            $data = $res['message'][0]['items'];
        }
        $signinfo = $this->Signmodel->getall(array(),0,20);
        foreach ($data as $key => $value) {
            $data_new = array();
            $data_new['message_id'] = isset($value['message_id'])?$value['message_id']:null;
            $data_new['job_id'] = $value['job_id'];
            $data_new['mobile'] = $value['mobile'];
            $data_new['content'] = $value['content'];
            $data_new['status'] = $value['status'];
            $data_new['type'] = $value['type'];
            $data_new['error'] = isset($value['error'])?$value['error']:null;
            $data_new['count'] = $value['count'];
            $data_new['created_at'] = date("Y-m-d H:i:s",$value['created_at']);
            # 拼配签名
            foreach ($signinfo as $keya => $valuea) {
                $signame = '【'.$valuea['signature'].'】';
                if(strpos($value['content'],$signame) !== false){ 
                    $data_new['sign_id'] = $valuea['sign_id'];
                    $data_new['sign_name'] = $valuea['signature'];
                }
            }
            # 数据是否存在
            $info = $this->Smslogmodel->select_smslog(array('job_id = ?' => $value['job_id'], 'mobile = ?' => $value['mobile']));
            if($info){
                $this->Smslogmodel->updated_smslog($info['id'],$data_new);
            }else{
                $this->Smslogmodel->insert($data_new);
            }
        }
        return array('res' => $data, 'signinfo' => $signinfo);
    }

    /**
     * 获取信息列表
     * @return [type] [description]
     */
    public function getsmsloglist(){
        $params = \PhalApi\DI()->request->getAll();
        $page = isset($params['page'])?$params['page']:1;
        $page_size = isset($params['page_size'])?$params['page_size']:10;
        $mobile = isset($params['mobile'])?$params['mobile']:"";
        $signvalue = isset($params['signvalue'])?$params['signvalue']:"";
        $where = array();
        $start = ($page-1)*$page_size;
        $end = $page*$page_size;
        if($mobile){
            $where['mobile = ?'] = $mobile;
        }
        if($signvalue){
            $where['sign_id = ?'] = $signvalue;
        }
        $res = $this->Smslogmodel->getall($where,$start,$end);
        $count = $this->Smslogmodel->getcount($where);
        return array('res' => $res, 'count' => intval($count));
    }

    /**
     * 获取用户列表
     * @return [type] [description]
     */
    public function getuserlist(){
        $params = \PhalApi\DI()->request->getAll();
        $page = isset($params['page'])?$params['page']:1;
        $page_size = isset($params['page_size'])?$params['page_size']:10;
        $mobile = isset($params['mobile'])?$params['mobile']:"";
        $name = isset($params['name'])?$params['name']:"";
        $where = array();
        $start = ($page-1)*$page_size;
        $end = $page*$page_size;
        if($mobile){
            $where['mobile = ?'] = $mobile;
        }
        if($name){
            $where['name = ?'] = $name;
        }
        $res = $this->Usermodel->getall($where,$start,$end);
        $count = $this->Usermodel->getcount($where);
        return array('res' => $res, 'count' => intval($count));
    }

    /**
     * 获取充值列表
     * @return [type] [description]
     */
    public function getrechargelist(){
        $params = \PhalApi\DI()->request->getAll();
        $page = isset($params['page'])?$params['page']:1;
        $page_size = isset($params['page_size'])?$params['page_size']:10;
        $name = isset($params['name'])?$params['name']:"";
        $where = array();
        $start = ($page-1)*$page_size;
        $end = $page*$page_size;
        $where = '1=1';
        if($name){
            $where .= " and name='$name'";
        }
        $res = $this->Rechargemodel->getall($where,$start,$end);
        $count = $this->Rechargemodel->getcount($where);
        return array('res' => $res, 'count' => intval($count));
    } 

    /**
     * 添加充值记录
     * @return [type] [description]
     */
    public function addrecharge(){
        $params = \PhalApi\DI()->request->getAll();
        $id = isset($params['user'])?$params['user']:'';
        $money = isset($params['money'])?$params['money']:'';
        if(empty($id)){
            return array('res' => '', 'msg' => '请选择用户');
        }
        if(empty($money)){
            return array('res' => '', 'msg' => '请填写充值金额');
        }
        $data = array();
        $data['user_id'] = $id;
        $data['money'] = $money;
        $data['created_at'] = date("Y-m-d H:i:s");
        $res = $this->Rechargemodel->insert($data);
        return array('res' => $res , 'msg' => '');
    }

    /**
     * 获取用户列表
     * @return [type] [description]
     */
    public function getuserinfolist(){
        $params = \PhalApi\DI()->request->getAll();
        $page = isset($params['page'])?$params['page']:1;
        $page_size = isset($params['page_size'])?$params['page_size']:10;
        $mobile = isset($params['mobile'])?$params['mobile']:"";
        $name = isset($params['name'])?$params['name']:"";
        $where = array();
        $start = ($page-1)*$page_size;
        $end = $page*$page_size;
        if($mobile){
            $where['mobile = ?'] = $mobile;
        }
        if($name){
            $where['name = ?'] = $name;
        }
        $res = $this->Usermodel->getall($where,$start,$end);
        foreach ($res as $key => $value) {
            # 获取用户下所有签名
            $sign = $this->UserSignmodel->getall($value['id']);
            $signinfo = '';
            $signarr = array();
            $signcheck = array();
            foreach ($sign as $keya => $valuea) {
                $signinfo .= '、'.$valuea['signature'];
                $signarr[] = $valuea['signid'];
                $signcheck[] = $valuea['sign_id'];
            }
            $res[$key]['signinfo'] = trim($signinfo,'、');
            $res[$key]['signcheck'] = $signcheck;
            # 充值金额
            $recharge = $this->Rechargemodel->countnum(array('user_id = ?' => $value['id']),'money');
            $res[$key]['money'] = $recharge;
            # 短信发送条数
            $smscount = 0;
            if($signarr){
                $smscount = $this->Smslogmodel->countnums('sign_id',$signarr,'count');
            }
            $res[$key]['usemoney'] = 0.04*$smscount;
            $res[$key]['number'] = $smscount;
        }
        $count = $this->Usermodel->getcount($where);
        return array('res' => $res, 'count' => intval($count));
    }

    /**
     * 获取用户可选签名
     * @return [type] [description]
     */
    public function getchecksign(){
        $params = \PhalApi\DI()->request->getAll();
        $id = isset($params['id'])?$params['id']:'';
        $res = array();
        if($id){
            $res = $this->UserSignmodel->getcanusesign($id);
        }
        return array('res' => $res);
    }

    /**
     * 更新用户签名
     * @return [type] [description]
     */
    public function updateusersign(){
        $params = \PhalApi\DI()->request->getAll();
        $id = isset($params['id'])?$params['id']:'';
        $check = isset($params['check'])?$params['check']:'';
        if(empty($id)){
            return array('res' => '', 'msg' => '信息错误');
        }
        $info = $this->UserSignmodel->getall($id);
        $signinfo = $this->Signmodel->getinfo();
        if(empty($check)){
            foreach ($info as $key => $value) {
                $this->UserSignmodel->deleteinfo("id",$value['id']);
            }
        }else{
            $checks = explode(",",trim($check,','));
            # 添加新签名
            foreach ($checks as $keya => $valuea) {
                $istrue = 1;
                foreach ($info as $keyb => $valueb) {
                    if($valuea == $valueb['signature']){
                        $istrue = 0;
                    }
                }
                if($istrue == 1){
                    $data = array();
                    $data['user_id'] = $id;
                    $data['created_at'] = date("Y-m-d H:i:s");
                    foreach ($signinfo as $keyc => $valuec) {
                        if($valuec['signature'] == $valuea){
                            $data['sign_id'] = $valuec['id'];
                        }
                    }
                    $this->UserSignmodel->insert($data);
                }
            }
            # 删除旧签名
            foreach ($info as $keyd => $valued) {
                $isdelete = 1;
                foreach ($checks as $keyf => $valuef) {
                    if($valued['signature'] == $valuef){
                        $isdelete = 0;
                    }
                }
                if($isdelete == 1){
                    $this->UserSignmodel->deleteinfo("id",$valued['id']);
                }
            }
        }
        return array('res' => $info, 'msg' => '');
    }

    /**
     * 二进制流文件上传接口
     * 
     * @param  array  $files  二进制流文件
     * @param  array  $filename  文件名
     * @param  array  $space  存储空间
     * @return [type] [code:返回码 message,返回信息]
     */
    public function uploadputfiles(){
        $params = \PhalApi\DI()->request->getAll();

        /*$image   = dirname(__FILE__).'/arow.png'; //图片地址
        $fp      = fopen($image, 'rb');
        $content = fread($fp, filesize($image)); //二进制数据
        $data = $this->QiNiuModel->uploadput($content,'arow.png','allergan');
        return $data;*/

        $files = isset($params['files'])?$params['files']:'';
        $filename = isset($params['filename'])?$params['filename']:'';
        $space = isset($params['space'])?$params['space']:'';
        if(empty($files)){
            $data = array();
            $data['code'] = "401";
            $data['message'] = "未找到文件";
            return $data;
        }
        if(empty($filename)){
            $data = array();
            $data['code'] = "402";
            $data['message'] = "未找到文件名";
            return $data;
        }
        $data = $this->QiNiuModel->uploadput($files,$filename,$space);
        return $data;
    }
} 
