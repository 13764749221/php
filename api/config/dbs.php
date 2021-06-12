<?php
/**
 * 分库分表的自定义数据库路由配置
 * 
 * @license     http://www.phalapi.net/license GPL 协议
 * @link        http://www.phalapi.net/
 * @author: dogstar <chanzonghuang@gmail.com> 2015-02-09
 */
if($_SERVER['HTTP_HOST']!='localhost:7001'){
    return array(
        /**
         * DB数据库服务器集群
         */
        'servers' => array(
            'db_master' => array(                       //服务器标记
                'type'      => 'mysql',                 //数据库类型，暂时只支持：mysql, sqlserver
                'host'      => 'rm-2ze85s44b59wp7672.mysql.rds.aliyuncs.com',            //数据库域名
                'name'      => 'web_h51',               //数据库名字
                'user'      => 'root',                  //数据库用户名
                'password'  => '8236455Wei',	                    //数据库密码
                'port'      => 3306,                    //数据库端口
                'charset'   => 'UTF8',                  //数据库字符集
            ),
        ),
    
        /**
         * 自定义路由表
         */
        'tables' => array(
            // 通用路由
            '__default__' => array(                     // 固定的系统标志，不能修改！
                'prefix' => '',                         // 数据库统一表名前缀，无前缀保留空
                'key' => 'id',                          // 数据库统一表主键名，通常为id
                'map' => array(                         // 数据库统一默认存储路由
                    array('db' => 'db_master'),         // db_master对应前面servers.db_master配置，须对应！
                ),
            ),
        ),
    );
}else{
    return array(
        /**
         * DB数据库服务器集群
         */
        'servers' => array(
            'db_master' => array(                       //服务器标记
                'type'      => 'mysql',                 //数据库类型，暂时只支持：mysql, sqlserver
                'host'      => 'localhost',            //数据库域名
                'name'      => 'test',               //数据库名字
                'user'      => 'root',                  //数据库用户名
                'password'  => '',	                    //数据库密码
                'port'      => 3306,                    //数据库端口
                'charset'   => 'UTF8',                  //数据库字符集
            ),
        ),
    
        /**
         * 自定义路由表
         */
        'tables' => array(
            // 通用路由
            '__default__' => array(                     // 固定的系统标志，不能修改！
                'prefix' => '',                         // 数据库统一表名前缀，无前缀保留空
                'key' => 'id',                          // 数据库统一表主键名，通常为id
                'map' => array(                         // 数据库统一默认存储路由
                    array('db' => 'db_master'),         // db_master对应前面servers.db_master配置，须对应！
                ),
            ),
        ),
    );
}

