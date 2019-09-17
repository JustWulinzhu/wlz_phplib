<?php
/**
 * Created by PhpStorm.
 * Author: wulinzhu
 * Email: linzhu.wu@beebank.com
 * Date: 19/8/30 下午3:21
 */

require_once "fun.php";

$db = new Db('sl');
$ret = $db->select(array('data' => '29301521774ff3cbd26652b2d5c95996'));
dd($ret);

for ($i=0; $i<100000; $i++) {
    $data = array(
        'serial_id' => base64_encode(mt_rand(0, 1000)),
        'appkey' => base64_encode(mt_rand(1000, 2000)),
        'case_id' => base64_encode(mt_rand(2000, 3000)),
        'status' => mt_rand(0, 1),
        'data' => md5(mt_rand(0, 10000)),
    );
//    $ret = $mysql->insert($data);
    Log::getInstance()->debug(array('mysql ret', $ret));
}