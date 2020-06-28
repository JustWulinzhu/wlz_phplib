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

require_once dirname(__DIR__) . "/Public/Init.php";

ini_set('memory_limit', '1024M');

//请求uri
$uri_arr = parse_url($_SERVER['REQUEST_URI']);
$uri = array_values(array_filter(explode("/", $uri_arr['path'])));

try {
    if ($uri && count($uri) > 2) {
        header(\S\Tools::http(404));
        exit();
    }
    $class = ucfirst(current($uri));
    if ($uri && empty($class)) {
        header(\S\Tools::http(404));
        exit();
    }
    $function = (1 == count($uri)) ? 'index' : end($uri);
    if (empty($uri)) { //网站默认首页
        $class = 'Home'; $function = 'index';
    }
    if ('Favicon.ico' == $class) { //网站icon
        exit((new \App\Controller\Image())->show(['image' => 'vip.ico']));
    }

    $namespace = '\\App\\Controller\\' . $class;
    $obj = new $namespace;
    $ret = $obj->$function(\S\Param::get());
    outputJson($ret);
} catch (\Throwable $e) {
    outputJson([], $e->getCode(), $e->getMessage());
}