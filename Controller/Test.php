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
     * @return array
     * @throws \Exception
     */
    public function index() {
        $mail = new Mail(Conf::getConfig('mail/exception'));
        $ret = $mail->send('18515831680@163.com', '异常报警', '邮件报警系统');
        dd($ret);
    }

}