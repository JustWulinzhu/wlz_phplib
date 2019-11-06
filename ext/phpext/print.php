<?php
/**
 * Created by PhpStorm.
 * Author: wulinzhu
 * Email: linzhu.wu@beebank.com
 * Date: 18/8/22 上午10:28
 */

function dd($arr = []) {
    if (empty($arr)) die;
    echo "<pre>";
    print_r($arr);
    echo "</pre>";
    die;
}

function ddd($arr = []) {
    if (empty($arr)) die;
    echo "<pre>";
    print_r($arr);
    echo "</pre>";
}

function pp($arr = []) {
    if (empty($arr)) die;
    echo "<pre>";
    var_dump($arr);
    echo "</pre>";
    die;
}

function ppp($arr = []) {
    if (empty($arr)) die;
    echo "<pre>";
    var_dump($arr);
    echo "</pre>";
    die;
}