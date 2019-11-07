<?php
/**
 * Created by PhpStorm.
 * Author: wulinzhu
 * Email: linzhu.wu@beebank.com
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