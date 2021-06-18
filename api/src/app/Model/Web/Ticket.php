<?php
namespace App\Model\Web;

use PhalApi\Model\NotORMModel as NotORM;

class Ticket extends NotORM {

	/**
	 * 手动设置表名
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	protected function getTableName($id) {
        return 'ticket';
	}
	
	/**
     * 根据条件查询数据是否存在
     * @param  [array] $where [查询条件]
     * @return [type]            [description]
     */
    public function select_where(){
        return $this->getORM()->fetchOne();
    }
}