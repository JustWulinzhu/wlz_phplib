<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 19/11/7 下午6:21
 * Email: 18515831680@163.com
 *
 * php自动加载
 *
 */

function autoLoadApp($class_name) {
    if (file_exists(str_replace("\\", DIRECTORY_SEPARATOR, APP_ROOT_PATH . "\\" . $class_name) . ".php")) {
        require (str_replace("\\", DIRECTORY_SEPARATOR, APP_ROOT_PATH . "\\" . $class_name) . ".php");
    }
}
function autoLoadAppS($class_name) {
    if (file_exists(str_replace("\\", DIRECTORY_SEPARATOR, APP_ROOT_PATH . "\\" . "S\\" . $class_name) . ".php")) {
        require (str_replace("\\", DIRECTORY_SEPARATOR, APP_ROOT_PATH . "\\" . "S\\" . $class_name) . ".php");
    }
}
spl_autoload_register('autoLoadApp');
spl_autoload_register('autoLoadAppS');