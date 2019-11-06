<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 19/11/5 下午3:07
 * Email: 18515831680@163.com
 *
 * php自动加载入口文件
 *
 * 注：采用命名空间的类与未采用命名空间的类自动加载机制有所区别
 *
 */

define("APP_ROOT_PATH", __DIR__); //项目绝对路径

require_once APP_ROOT_PATH . "/fun.php";

//采用命名空间的类
$namespaced_class = [
    'redis',
    'queue',
];

$files = Fun::scanDir(APP_ROOT_PATH);
//根目录一级文件名
$file_names_keys = array_filter($files, function ($v) { if (! is_array($v)) {return $v;}} );
$file_names_keys = array_map(function ($x) { return str_replace('.php', '', $x); }, $file_names_keys);
//根目录二级文件名
$file_names_values = array_values(array_filter(array_keys($files), function ($v) { if (! is_numeric($v)) { return $v;} } ));

foreach ($file_names_keys as $file_name) {
    $function_str =
        'function autoLoadApp'.ucfirst($file_name).'($class_name) {
            $class_name = strtolower($class_name);
            if (file_exists(APP_ROOT_PATH . DIRECTORY_SEPARATOR . "/{$class_name}.php")) {
                require (APP_ROOT_PATH . DIRECTORY_SEPARATOR . "/{$class_name}.php");
            }
        }';
    eval($function_str);
    spl_autoload_register('autoLoadApp' . ucfirst($file_name));
}

foreach ($file_names_values as $file_name) {
    if (in_array($file_name, $namespaced_class)) { //采用命名空间方式的类
        $function_str =
            'function autoLoadApp'.ucfirst($file_name).'($class_name) {
                $class_name = strtolower($class_name);
                if (file_exists(str_replace("\\\", DIRECTORY_SEPARATOR, APP_ROOT_PATH . "\\\" . $class_name) . ".php")) {
                    require (str_replace("\\\", DIRECTORY_SEPARATOR, APP_ROOT_PATH . "\\\" . $class_name) . ".php");
                }
            }';
    } else { //未采用命名空间的类
        $function_str =
            'function autoLoadApp'.ucfirst($file_name).'($class_name) {
                $class_name = strtolower($class_name);
                if (file_exists(APP_ROOT_PATH . DIRECTORY_SEPARATOR . "' .$file_name. '/{$class_name}.php")) {
                    require (APP_ROOT_PATH . DIRECTORY_SEPARATOR . "' .$file_name. '/{$class_name}.php");
                }
            }';
    }
    eval($function_str);
    spl_autoload_register('autoLoadApp' . ucfirst($file_name));
}

$uri = $_SERVER['REQUEST_URI'];
$uri_arr = array_filter(explode('/', $uri));
$uri_arr = array_map(function ($x) { return ucfirst($x); }, $uri_arr);
$namespace = '';
foreach ($uri_arr as $uri) {
    $namespace .= $uri . '\\';
}
$obj = trim($namespace, '\\');
try {
    $obj = new $obj;
    $ret = $obj::request('http://mapglobal.baidu.com/mapsguide/hotcity?format=json&_=1517563094325');
} catch (\Throwable $e) {
    die('404 Not Found.');
}
print_r($ret);

//print_r(spl_autoload_functions());