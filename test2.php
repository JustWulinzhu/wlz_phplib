<?php
/**
 * Created by PhpStorm.
 * Author: wulinzhu
 * Email: linzhu.wu@beebank.com
 * Date: 19/6/25 上午11:20
 */
require_once "fun.php";

//递归测试
function test($a) {
    if ($a > 5) {
        Log::getInstance()->debug(array($a, 1));
        test($a - 1);
    } else {
        Log::getInstance()->debug(array($a, 2));
        return;
    }
}
test(10);