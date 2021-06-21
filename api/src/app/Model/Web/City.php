<?php
namespace App\Model\Web;

use PhalApi\Model\NotORMModel as NotORM;

class City extends NotORM {

	/**
	 * 手动设置表名
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	protected function getTableName($id) {
        return 'city';
    }

    /**
     * 获取全部数据
     * @return [type]            [description]
     */
    public function getall($where = ""){
        $sql = "SELECT city_code FROM city  WHERE province_code='$where'";
        return $this->getORM()->queryAll($sql, array());
    }

     /**
     * 获取特定数据
     */
    public function groupall(){
        $sql = "SELECT province_code FROM city  GROUP BY province_code";
        return $this->getORM()->queryAll($sql, array());
    }
}