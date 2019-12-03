<?php

namespace Controller;

use S\Db;
use S\Excel;
use S\Exceptions;
use S\Fun;
use S\Mail;
use S\Queue\Redis\Redis;
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
     * @param null $arr
     * @return mixed
     * @throws \Exception
     */
    public function index($arr = null) {
        $a = new Redis();
        $ret = $a->push('test', '武林柱是个大帅哥');
        return $ret;
    }

}