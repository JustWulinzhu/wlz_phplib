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
        $src = "/tmp/cfca/bg.jpg";
        $gd = new \S\Gd($src);

        $source = "/tmp/cfca/bg.jpg";
        $gd -> waterMarkImage($source, 0, 0, 30);
        $gd -> thumb(200, 80);

        $fontPath = "/www/tmp/arialuni.ttf";
        $text = "赵子龙";
        $gd -> waterMarkText(
            $text,
            $fontPath,
            24,
            array(0, 0, 0, 20),
            20,
            50,
            0);

        $gd -> show();
        $gd -> save("image_mark");
    }

}