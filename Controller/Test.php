<?php

namespace Controller;

use S\Db;
use S\Excel;
use S\Url;
use S\Log;
use S\Curl;
use S\Crypt\Aes;
use S\Crypt\Rsa;
use S\Oss\Oss;
use Config\Conf;
use S\Redis\Lock;
use S\Queue\Mns\Mns;
use S\Redis\BaseRedis as BaseRedis;
use S\Queue\Redis\Redis as QueueRedis;

class Test {

    /**
     * @return array
     * @throws \Exception
     */
    public function index() {
        $ret = (new Excel())->read('/www/tmp/2019-04-15账务盒子汽车还HX数据.csv');
        dd($ret);
    }

}