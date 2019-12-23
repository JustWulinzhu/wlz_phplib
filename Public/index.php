<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 19/11/5 下午3:07
 * Email: 18515831680@163.com
 *
 * php cgi模式入口文件
 *
 * 默认Controller/xxx.php为请求接收地址
 *
 * demo:
 * 请求地址：myhost/index/index?name=xxx&age=18
 * 请求会指到Controller下面的Index.php里面的index方法，index方法参数即为请求参数name=xxx&age=18
 *
 */

define("APP_ROOT_PATH", dirname(__DIR__)); //项目绝对路径
require_once APP_ROOT_PATH . "/Ext/phpext/F.php";
require_once APP_ROOT_PATH . "/Public/Autoload.php";

//请求uri
$uri_arr = parse_url($_SERVER['REQUEST_URI']);
$uri = array_values(array_filter(explode("/", $uri_arr['path'])));

//请求参数
$params = array_filter(explode("&", isset($uri_arr['query']) ? $uri_arr['query'] : ''));
$request_params = [];
foreach ($params as $param) {
    $value = explode("=", $param);
    $request_params[current($value)] = end($value);
}

try {
    if (count($uri) > 2) {
        throw new Exception('404 Not Found.', 404);
    }
    $class = ucfirst(current($uri));
    if (empty($class)) {
        throw new Exception('404 Not Found.', 404);
    }
    $function = (1 == count($uri)) ? 'index' : end($uri);
    $namespace = '\\Controller\\' . $class;
    $obj = new $namespace;
    $ret = $obj->$function($request_params);
    outputJson($ret);
} catch (\Throwable $e) {
    if ($e->getCode() == 404) {
        outputJson([], $e->getCode(), $e->getMessage());
        exit();
    }
    throw $e;
}