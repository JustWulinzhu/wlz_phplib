<?php
/**
 * Created by PhpStorm.
 * Author: wulinzhu
 * Email: 18515831680@163.com
 * Date: 2022/2/18 上午10:21
 *
 * 单链表实现
 *
 */

namespace S\Suanfa;

class Node {

    public $value; //节点值
    public $next; //指针，下个节点

    public function __construct($value = null, $next = null) {
        $this->value = $value;
        $this->next = $next;
    }

}

namespace S\Suanfa;

class LinkedList {

    private $head;

    //头插法
    public function addHead($value) {
        $node = new Node($value); //实例化一个节点
        $this->head = $node; //把实例化的节点赋值给头部
        $node->next = new Node(); //初始化下个节点
    }

    public function add($value) {
        $node = $this->init($value);

        return $node;
    }

    //初始化一个节点
    private function init($value) {
        $node = new Node($value);
        $node->next = new Node();
        return $node;
    }

}