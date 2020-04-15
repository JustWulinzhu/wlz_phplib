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
        $files = [
            '/tmp/a.txt',
            '/tmp/b.txt',
        ];
        (new \S\Compress\Zip())->create($files, '/tmp/test.zip');
    }

}