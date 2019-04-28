<?php
/**
 * Created by PhpStorm.
 * Author: wulinzhu
 * Email: linzhu.wu@beebank.com
 * Date: 18/8/22 上午10:28
 */

function ff($arr) {
    echo "<pre>";
    print_r($arr);
    echo "\n";
}

function dd($arr = []) {
    if (empty($arr)) die;
    echo "<pre>";
    print_r($arr);
    echo "</pre>";
    die;
}

function vv($arr = []) {
    if (empty($arr)) die;
    echo "<pre>";
    var_dump($arr);
    echo "</pre>";
    die;
}

function pp($arr) {
    var_dump($arr);
    die;
}