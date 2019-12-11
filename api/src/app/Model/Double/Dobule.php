<?php
namespace App\Model\Double;

use PhalApi\Model\NotORMModel as NotORM;

class Dobule extends NotORM {

	/**
	 * 手动设置表名
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	protected function getTableName($id) {
        return 'qrcode';
    }

    public function getall($where,$start,$end){
        return $this->getORM()->where($where)->limit($start,$end)->fetchAll();
    }

    public function getcount($where){
        return $this->getORM()->where($where)->count();
    }
}