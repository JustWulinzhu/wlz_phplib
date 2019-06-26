<?php
/**
 * Created by PhpStorm.
 * Author: wulinzhu
 * Email: linzhu.wu@beebank.com
 * Date: 19/6/25 上午11:20
 */
require_once "fun.php";

$ipc = new Ipc();
$value = $ipc->get('key');
dd($value);die;