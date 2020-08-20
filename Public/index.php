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

try {
    $uri_arr = parse_url($_SERVER['REQUEST_URI']);
    $uri = array_values(array_filter(explode("/", $uri_arr['path'])));
    $uri = array_map(function ($i) { return ucfirst($i); }, $uri);
    $class = implode("\\", $uri);

    if ('Favicon.ico' == $class) {
        exit((new \App\Controller\Image())->show(['image' => 'vip.ico']));
    }

    $namespace = '\\App\\Controller\\' . $class;
    $obj = new $namespace;
    $ret = $obj->index(\S\Param::get());
    outputJson($ret);
} catch (\Throwable $e) {
    outputJson([], $e->getCode(), $e->getMessage());
}