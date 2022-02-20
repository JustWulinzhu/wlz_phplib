<?php
/**
 * Created by PhpStorm.
 * Author: wulinzhu
 * Email: linzhu.wu@beebank.com
 * Date: 21/9/7 上午10:57
 *
 * 数据结构：栈
 * 先进后出 First In Last Out
 *
 */

namespace S\Datastructure;

class Stack {

    private $container;

    /**
     * Stack constructor.
     */
    public function __construct() {
        $this->container = [];
    }

    /**
     * @return array
     */
    public function get() {
        return $this->container;
    }

    /**
     * 入栈
     *
     * @param $v
     * @return int
     */
    public function push($v) {
         return array_push($this->container, $v);
    }

    /**
     * 出栈
     *
     * @return mixed
     */
    public function pop() {
        return array_pop($this->container);
    }

    /**
     * @return int
     */
    public function length() {
        return count($this->container);
    }

    /**
     * @return bool
     */
    public function isEmpty() {
        return count($this->container) == 0;
    }

}