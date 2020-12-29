<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 19/12/24 下午2:41
 * Email: 18515831680@163.com
 *
 * 初始化相关，需要在入口文件首先引入
 *
 */

//项目绝对路径
define("APP_ROOT_PATH", dirname(__DIR__));
//项目脚本路径
define("APP_JOB_PATH", __DIR__);
//项目Static路径
define("APP_STATIC_PATH", APP_ROOT_PATH . DIRECTORY_SEPARATOR . 'Public/Static');
//项目host
define("APP_HOST", 'www.wlfeng.vip');
//项目域名
define("APP_DOMAIN", 'https://' . APP_HOST);
//图片服务器
define("IMAGE_SERVER_DOMAIN", APP_HOST . ':8081');

//顺序不可变
require_once APP_ROOT_PATH . "/Public/Autoload.php";
require_once APP_ROOT_PATH . "/Ext/Smarty/Smarty.class.php";
require_once APP_ROOT_PATH . "/Ext/phpext/F.php";