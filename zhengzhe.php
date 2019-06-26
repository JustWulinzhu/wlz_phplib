<?php
/**
 * Created by PhpStorm.
 * Author: wulinzhu
 * Email: linzhu.wu@beebank.com
 * Date: 19/6/13 下午3:29
 */

include_once "fun.php";

$str = 'linzhu.wu@beebank.com';
$rules = '/[0-9a-zA-Z_\-\.]{3,16}@[a-zA-Z0-9]+\.(com|cn)/';
$ret = preg_match($rules, $str, $match);
dd($match);