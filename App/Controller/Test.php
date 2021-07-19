<?php

namespace App\Controller;

use S\Db;
use S\Office\Excel;
use S\Exceptions;
use S\Soap\Server;
use \S\Tools;
use S\Mail;
use S\Oss\Files;
use S\Queue\Redis\Redis;
use S\Url;
use S\Log;
use S\Http\Curl;
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
     * @return string
     * @throws \Exception
     */
    public function index($arr = null) {
        (new \S\Api\Sms\Tskj())->send('18515831680', 'TP1710262', ['code' => random_int(100000, 999999)]);
    }

    public function demo() {
        $ret = (new Db('url'))->select(['key' => 12345678]);
        return $ret;
    }

}