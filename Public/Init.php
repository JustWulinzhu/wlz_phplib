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
//项目host
define("APP_HOST", 'www.wlfeng.vip');
//项目域名
define("APP_DOMAINS", 'https://' . APP_HOST);
define("APP_DOMAIN", 'http://' . APP_HOST);
//项目Static地址
define("APP_STATIC_PATH", APP_DOMAIN . '/static');
define("APP_STATIC_PATHS", APP_DOMAINS . '/static');
//图片服务器
define("IMAGE_SERVER_DOMAIN", 'http://image.wlfeng.vip');

//顺序不可变
require_once APP_ROOT_PATH . "/Public/Autoload.php";
require_once APP_ROOT_PATH . "/Ext/Smarty/Smarty.class.php";
require_once APP_ROOT_PATH . "/Ext/phpext/F.php";

//打开错误提示
ini_set("display_errors", "On");
//显示所有错误
ini_set("error_reporting",E_ALL);