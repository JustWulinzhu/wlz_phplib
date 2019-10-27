<?php
/**
 * Created by PhpStorm.
 * Author: wulinzhu
 * Email: linzhu.wu@beebank.com
 * Date: 18/12/14 下午5:26
 * redis队列简单封装,先进先出
 * 队列消费进程 /www/wlz_phplib/shell/pop.sh, 每秒执行一次,模仿长进程
 *
 * 压入队列 (new Queue())->push('key', 'value');
 * 弹出队列 (new Queue())->pop('key'); pop
 * 最大队列长度10000
 */

require_once dirname(__DIR__) . "/fun.php";
require_once dirname(__DIR__) . "/redis/baseRedis.php";

class Queue extends BaseRedis {

    const QUEUE = 'REDIS_QUEUE_';

    const MAX_QUEUE_NUM = 10000;

    /**
     * 拼接key
     * @param $key
     * @return string
     * @throws Exceptions
     */
    private function getKey($key) {
        if (! is_string($key)) {
            throw new Exceptions('键值必须为字符串');
        }
        return self::QUEUE . $key;
    }

    /**
     * 压入队列
     * @param $key
     * @param $value
     * @return int
     * @throws Exceptions
     * @throws Exception
     */
    public function push($key, $value) {
        if ($this->lLen($key) >= self::MAX_QUEUE_NUM) {
            throw new Exceptions('队列已满');
        }
        return $this->getInstance()->lPush($this->getKey($key), $value);
    }

    /**
     * 弹出队列
     * @param $key
     * @return bool|string
     * @throws Exception
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
     * @return int
     * @throws Exceptions
     * @throws Exception
     */
    public function lLen($key) {
        return $this->getInstance()->lLen($this->getKey($key));
    }

}