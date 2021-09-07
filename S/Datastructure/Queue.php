<?php
/**
 * Created by PhpStorm.
 * Author: wulinzhu
 * Email: linzhu.wu@beebank.com
 * Date: 21/9/7 下午2:14
 *
 * 数据结构：队列
 * 先进先出 First In First Out
 *
 */

namespace S\Datastructure;

class Queue {

    private $list;

    /**
     * Queue constructor.
     */
    public function __construct() {
        $this->list = [];
    }

    /**
     * @return array
     */
    public function get() {
        return $this->list;
    }

    /**
     * @param $v
     * @return int
     */
    public function push($v) {
        return array_unshift($this->list, $v);
    }

    /**
     * @return mixed
     */
    public function pop() {
        return array_pop($this->list);
    }

    /**
     * @return int
     */
    public function length() {
        return count($this->list);
    }

    /**
     * @return bool
     */
    public function isEmpty() {
        return empty($this->list);
    }

}