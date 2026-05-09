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
        phpinfo();
        $arr = [
            [
                'name' => '张三',
                'age' => 18,
                'sex' => '男'
            ],
            [
                'name' => '李四',
                'age' => 19,
                'sex' => '男'
            ]
        ];
        $newArr = Tools::arraySort($arr, 'age', 'desc');
        dd($newArr);
    }

    public function lua() {
        try {
            $redis = (new BaseRedis())->getInstance(BaseRedis::REDIS_MOD_SINGLE);
            if ($redis->set("lock:clientId:111", 123, "NX", "EX", 10)) {
                echo "加锁成功";
                //处理业务 or 进MQ异步处理
            }
        } catch (\Exception $e) {
            //记录业务日志
        } finally {
            // 释放锁 调用lua脚本原子操作删除锁
            $luaScript = <<<LUA
local lockKey = KEYS[1];    --加锁的key
local clientId = ARGV[1];   --锁key的value
if redis.call("GET", lockKey) == clientId then
    redis.call("DEL", lockKey); --是自己的锁，进行删除
    return 1;
else
    return 0; --不是自己锁，返回0
end
LUA;
            $lockDel = $redis->eval($luaScript, ["lock:clientId:111", 123], 1);
            if ($lockDel) {
                echo "删除成功";
            } else {
                echo "删除失败";
            }
        }

    }


}