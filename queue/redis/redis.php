<?php
/**
 * Created by PhpStorm.
 * Author: wulinzhu
 * Email: linzhu.wu@beebank.com
 * Date: 18/12/14 下午5:26
 * redis队列简单封装,先进先出
 * 队列监听消费进程, 启动脚本：php /www/wlz_phplib/queue/redis/task.php $queue_name &, 每秒执行一次,模仿长进程
 *
 * 压入队列 (new \Queue\Redis\Redis())->push('key', 'value');
 * 弹出队列 (new \Queue\Redis\Redis())->pop('key');
 * 最大队列默认长度10000
 *
 */

namespace Queue\Redis;

use Redis\BaseRedis;

require_once dirname(dirname(__DIR__)) . "/fun.php";
require_once dirname(dirname(__DIR__)) . "/redis/baseRedis.php";

class Redis extends BaseRedis {

    const QUEUE = 'REDIS_QUEUE_';

    const MAX_QUEUE_NUM = 10000;

    /**
     * 拼接key
     * @param $key
     * @return string
     * @throws \Exception
     */
    private function getKey($key) {
        if (! is_string($key)) {
            throw new \Exception('键值必须为字符串');
        }
        return self::QUEUE . $key;
    }

    /**
     * 压入队列
     * @param $key
     * @param $value
     * @return mixed
     * @throws \Exception
     */
    public function push($key, string $value) {
        if ($this->lLen($key) >= self::MAX_QUEUE_NUM) {
            throw new \Exception('队列已满');
        }
        return $this->getInstance()->lPush($this->getKey($key), $value);
    }

    /**
     * 弹出队列
     * @param $key
     * @return bool|mixed
     * @throws \Exception
     */
    public function pop($key) {
        if (0 === $this->lLen($key)) {
            return false;
        }
        return $this->getInstance()->rPop($this->getKey($key));
    }

    /**
     * 获取队列长度
     * @param $key
     * @return bool|int
     * @throws \Exception
     */
    public function lLen($key) {
        return $this->getInstance()->lLen($this->getKey($key));
    }

}