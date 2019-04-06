<?php
/**
 * Created by PhpStorm.
 * Author: wulinzhu
 * Email: linzhu.wu@beebank.com
 * Date: 18/12/14 下午5:26
 * redis队列简单封装,先进先出
 */

class Queue {

    private $redis = null;

    private function getInstance() {
        if (is_null($this->redis)) {
            $this->redis = new \Redis();
            $this->redis->connect('106.13.34.80', '6379');
        }
        return $this->redis;
    }

    /**
     * 压入队列
     * @param $key
     * @param $value
     * @return int
     */
    public function push($key, $value) {
        return $this->getInstance()->lPush($key, $value);
    }

    /**
     * 弹出队列
     * @param $key
     * @return bool|string
     */
    public function pop($key) {
        if (0 === $this->lLen($key)) {
            return false;
        }
        return $this->getInstance()->rPop($key);
    }

    /**
     * 获取队列长度
     * @param $key
     * @return int
     */
    public function lLen($key) {
        return $this->getInstance()->lLen($key);
    }

}