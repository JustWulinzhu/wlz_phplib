<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 19/11/5 下午3:07
 * Email: 18515831680@163.com
 *
 * php自动加载入口文件
 *
 */

define("APP_ROOT_PATH", dirname(__DIR__)); //项目绝对路径
require_once APP_ROOT_PATH . "/S/fun.php";

function autoLoadApp($class_name) {
    if (file_exists(str_replace("\\", DIRECTORY_SEPARATOR, APP_ROOT_PATH . "\\" . $class_name) . ".php")) {
        require (str_replace("\\", DIRECTORY_SEPARATOR, APP_ROOT_PATH . "\\" . $class_name) . ".php");
    }
}
spl_autoload_register('autoLoadApp');

$uri = $_SERVER['REQUEST_URI'];
$uri = ucfirst(str_replace("/", '', $uri));
try {
    $namespace = '\\S\\' . $uri;
    new $namespace;
} catch (\Throwable $e) {
    die('404 Not Found.');
}