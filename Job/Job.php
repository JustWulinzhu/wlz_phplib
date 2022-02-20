<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 19/11/7 下午5:29
 * Email: 18515831680@163.com
 *
 * Cli模式入口文件（脚本执行入口文件）
 * 内存使用512M
 * 开启错误输出
 *
 */

require_once dirname(__DIR__) . "/Public/Init.php";
ini_set('memory_limit', '512M');
ini_set("display_errors", 'on');

if (! \S\Tools::isCli()) die("cli mod only...");
if (count($argv) <= 1) die('404 Not Found.');

$params = [];
foreach ($argv as $key => $param) {
    if ($key > 1) $params[] = $param;
}

$path = $argv[1];
$path = array_map(function ($x) {return ucfirst($x); }, explode("_", $path));
$path = str_replace("/", "\\", implode("/", $path));
$class = "\\" . $path;

try {
    $ret = (new $class)->exec($params);
    print_r($ret);
} catch (\Throwable $e) {
    die($e->getMessage());
}