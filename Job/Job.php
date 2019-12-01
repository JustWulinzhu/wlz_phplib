<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 19/11/7 下午5:29
 * Email: 18515831680@163.com
 *
 * Cli模式入口文件（脚本执行入口文件）
 *
 */

if (! \S\Fun::isCli()) {
    die("cli mod only...");
}

define("APP_ROOT_PATH", dirname(__DIR__));
define("APP_JOB_PATH", __DIR__);
require_once APP_ROOT_PATH . "/Ext/phpext/F.php";
require_once APP_ROOT_PATH . "/Public/Autoload.php";
ini_set('memory_limit', '512M');

if (count($argv) <= 1) die('404 Not Found.');

$params = [];
foreach ($argv as $key => $param) {
    if ($key > 1) $params[] = $param;
}

$path = $argv[1];
$path = array_map(function ($x) {return ucfirst($x); }, explode("_", $path));
$path = str_replace("/", "\\", implode("/", $path));
$class = "\\Job\\" . $path;

try {
    (new $class)->exec($params);
} catch (\Throwable $e) {
    die($e->getMessage());
}