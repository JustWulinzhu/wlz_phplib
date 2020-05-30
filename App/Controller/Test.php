<?php

namespace App\Controller;

use S\Db;
use S\Office\Excel;
use S\Exceptions;
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
     * @throws Exceptions
     * @throws \Exception
     */
    public function index($arr = null) {
        $ret = \S\Qrcode::create('http://wlfeng.vip/image/show?image_name=jiehun1.jpg', '/www/tmp/image/gou.png');
        //$ret = \S\Qrcode::read($ret);
        (new \S\Image())->show(base64_encode(file_get_contents($ret)));
    }

    public function demo() {
        return [];
    }

}