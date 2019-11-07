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

define("APP_ROOT_PATH", dirname(__DIR__));
define("APP_JOB_PATH", __DIR__);
require_once APP_ROOT_PATH . "/Ext/phpext/print.php";
require_once APP_ROOT_PATH . "/Public/Autoload.php";

if (count($argv) <= 1) die('no jobs input.');

$params = [];
foreach ($argv as $key => $param) {
    if ($key > 1) {
        $params[] = $param;
    }
}

$path = $argv[1];
$path = array_map(function ($x) {return ucfirst($x); }, explode("_", $path));
$path = implode("/", $path) . '.php';
$file_path = APP_JOB_PATH . DIRECTORY_SEPARATOR . $path;

$argv = $params;

if (! file_exists($file_path)) {
    die("file " . '"' . "$file_path" . '"' . " Not Found.");
}
require $file_path;