<?php

namespace App\Controller;

use S\Db;
use S\Office\Excel;
use S\Exceptions;
use S\Param;
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

class Test extends \App\Controller\Base {

    protected $verify = false;

    /**
     * @param null $arr
     * @return string
     * @throws \Exception
     */
    public function index($arr = null) {
        throw new \S\Exceptions("测试异常AAA");
        $files = Tools::scanDir("/data1/shell");
        dd($files);
        $test = (new BaseRedis())->getInstance()->get("a");
        echo $test;
        $redis = (new BaseRedis())->getInstance();
        $redis->set("b", "b");
        dd($redis->get("b"));
//        $a = crc32("image.wlfeng.vip");
//        echo $a;
        $secret = hash("crc32", "image.wlfeng.vip");
        dd($secret);
        $ret = Tools::insertStr("abcd", 2, "xxoo");
        dd($ret);
        Tools::myStrReplace("abcdbcfff", "bc" , "xxoo");


        $str = crc32("wulinzhu");
        $num = $str % 100;
        dd($num);

        $lock = (new Lock())->mutexLock("lock_key");
        if ($lock) {
            Log::getInstance()->debug(['success']);
        } else {
            Log::getInstance()->warning(['fail']);
        }
    }

    public function demo() {
        $ret = (new Db('url'))->select(['key' => 12345678]);
        return $ret;
    }

}