<?php
/**
 * Created by PhpStorm.
 * Author: wulinzhu
 * Email: 18515831680@163.com
 * Date: 18/12/14 下午5:26
 * redis队列简单封装,先进先出
 *
 * 压入队列 (new \Queue\Redis\Redis())->push('key', 'value');
 * 弹出队列 (new \Queue\Redis\Redis())->pop('key');
 * 最大队列默认长度10000
 *
 */

namespace S\Queue\Redis;

use S\Exceptions;
use \S\Redis\BaseRedis;

class Redis extends BaseRedis {

    const QUEUE = 'REDIS_QUEUE_';
    const MAX_QUEUE_NUM = 100000;

    private $redisInstance = null;

    public function __construct() {
        $this->redisInstance = $this->getInstance(self::REDIS_MOD_SINGLE);
    }

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
        return $this->redisInstance->lPush($this->getKey($key), $value);
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
        return $this->redisInstance->rPop($this->getKey($key));
    }

    /**
     * 弹出多个队列，位置在前的key优先弹出，如果前面的key没数据的话才谈后面的，超时时间内是阻塞状态
     * 可用做设计优先级队列，开启双队列
     * @param array $keys
     * @return array|false
     */
    public function brPop($keys = []) {
        if (empty($keys) || (! is_array($keys))) {
            return false;
        }
        return $this->redisInstance->brPop($keys, 5);
    }

    /**
     * 获取队列长度
     * @param $key
     * @return bool|int
     * @throws \Exception
     */
    public function lLen($key) {
        return $this->redisInstance->lLen($this->getKey($key));
    }

}