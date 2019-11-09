<?php

namespace Controller;

use S\Db;
use S\Excel;
use S\Mail;
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
     * @throws \Exception
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function index() {
        Log::getInstance()->error(['error_log_test']);
    }

}