<?php
/**
 * Created by PhpStorm.
 * Author: wulinzhu
 * Email: linzhu.wu@beebank.com
 * Date: 18/8/27 下午5:37
 * 进程间缓存,进程结束缓存随之失效,适合同一次进程间对某些变量的频繁操作
 */

class Ipc {

    private $storage = array();
    private $size = 0;

    /**
     * @param $key
     * @return bool|mixed
     */
    public function get($key) {
        if (isset($this->storage[$key])) {
            return $this->storage[$key];
        }
        return false;
    }

    /**
     * 设置进程缓存,$max切勿设置过大,除非确定不会引起内存泄露之类问题
     * @param $key
     * @param $value
     * @param int $max
     * @return bool
     */
    public function set($key, $value, $max = 60) {
        if ($this->size > $max) {
            array_shift($this->storage);
        }
        $this->storage[$key] = $value;
        $this->size++;
        return true;
    }

    /**
     * @param $key
     * @return bool
     */
    public function del($key) {
        if (isset($this->storage[$key])) {
            unset($this->storage[$key]);
            return true;
        }
        return false;
    }

}