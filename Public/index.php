<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 19/11/5 下午3:07
 * Email: 18515831680@163.com
 *
 * php cgi模式入口文件
 *
 */

define("APP_ROOT_PATH", dirname(__DIR__)); //项目绝对路径
require_once APP_ROOT_PATH . "/Ext/phpext/print.php";
require_once APP_ROOT_PATH . "/Public/Autoload.php";

$uri = $_SERVER['REQUEST_URI'];
$uri = array_values(array_filter(explode("/", $uri)));
try {
    $class = ucfirst(current($uri));
    if (empty($class)) {
        die('class Not Found.');
    }
    $function = end($uri);
    $namespace = '\\Controller\\' . $class;
    $obj = new $namespace;
    $obj->$function();
} catch (\Throwable $e) {
    die($e->getMessage());
}