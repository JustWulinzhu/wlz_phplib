<?php
/**
 * Created by PhpStorm.
 * Author: wulinzhu
 * Email: 18515831680@163.com
 * Date: 18/8/22 上午10:28
 *
 * 初始化全局函数
 *
 */

function dd($arr) {
    echo "<pre>";
        print_r($arr);
    echo "</pre>";
    die;
}

function ddd($arr) {
    echo "<pre>";
        print_r($arr);
    echo "</pre>";
}

function pp($arr) {
    echo "<pre>";
        var_dump($arr);
    echo "</pre>";
    die;
}

function ppp($arr = []) {
    echo "<pre>";
        var_dump($arr);
    echo "</pre>";
}

function outputJson($data = [], $code = 200, $msg = '成功') {
    $arr = [
        'code'  => $code,
        'msg'   => $msg,
        'data'  => is_null($data) ? [] : $data,
    ];
    if (200 != $code) {
        unset($arr['data']);
    }
    exit(json_encode($arr));
}

function S() {
    $conf = \Config\Conf::getConfig('smarty');

    $smarty = new \Smarty();
    //自定义模板目录
    $smarty->template_dir = $conf['template_dir'];
    //自定义编译目录
    $smarty->compile_dir = $conf['compile_dir'];
    //自定义变量目录
    $smarty->config_dir = $conf['config_dir'];
    //缓存目录
    $smarty->cache_dir = $conf['cache_dir'];
    //是否缓存
    $smarty->caching = $conf['caching'];

    return $smarty;
}