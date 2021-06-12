<?php
namespace App\Model\Web;

use PhalApi\Model\NotORMModel as NotORM;

class Question extends NotORM {

	/**
	 * 手动设置表名
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	protected function getTableName($id) {
        return 'question';
    }
}