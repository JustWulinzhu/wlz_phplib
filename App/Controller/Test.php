<?php

namespace App\Controller;

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
     * @return bool
     * @throws \Exception
     */
    public function index($arr = null) {
        $ret = Rsa::encrypt("各位儿子们，爸爸来了");
        $ret = Rsa::decrypt($ret);
        dd($ret);
    }

}