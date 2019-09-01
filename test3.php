<?php
/**
 * Created by PhpStorm.
 * Author: wulinzhu
 * Email: linzhu.wu@beebank.com
 * Date: 19/8/30 下午3:21
 */

require_once "fun.php";

$mysql = new Mysql('sl');

for ($i=0; $i<10000; $i++) {
    $data = array(
        'serial_id' => base64_encode(mt_rand(0, 1000)),
        'appkey' => base64_encode(mt_rand(1000, 2000)),
        'case_id' => base64_encode(mt_rand(2000, 3000)),
        'status' => mt_rand(0, 1),
        'data' => md5(mt_rand(0, 10000)),
    );
    $ret = $mysql->insert($data);
    Log::getInstance()->debug(array('mysql ret', $ret));
}