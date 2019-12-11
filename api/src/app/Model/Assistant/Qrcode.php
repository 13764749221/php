<?php
namespace App\Model\Assistant;

use PhalApi\Model\NotORMModel as NotORM;

class Qrcode extends NotORM {

	/**
	 * 手动设置表名
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	protected function getTableName($id) {
        return 'qrcode';
    }

    /**
     * 获取列表数据
     * @param  [array] $where [查询条件]
     * @param  [int] $start [分页初始值]
     * @param  [int] $end   [分页截止值]
     * @param  [string] $order   [排序]
     * @return [array]        [description]
     */
    public function getall($where,$start,$end,$order){
        return $this->getORM()->where($where)->limit($start,$end)->order($order)->fetchAll();
    }

    /**
     * 获取查询数
     * @param  [array] $where [查询条件]
     * @return [array]        [description]
     */
    public function getcount($where){
        return $this->getORM()->where($where)->count();
    }
}