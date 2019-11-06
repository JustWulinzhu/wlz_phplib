<?php

namespace Controller;

use S\Db;
use S\Url;
use S\Log;
use S\Curl;
use S\Crypt;
use S\Oss\Oss;
use Config\Conf;
use S\Redis\Lock;
use S\Redis\BaseRedis;
use S\Queue\Redis\Redis;


class Test {

    public function index() {
        $db = new Db('sl');
        $ret = $db->select(['id' => 1]);
        dd($ret);
    }

}