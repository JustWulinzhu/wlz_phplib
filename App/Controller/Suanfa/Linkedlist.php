<?php
/**
 * Created by PhpStorm.
 * Author: wulinzhu
 * Email: 18515831680@163.com
 * Date: 2022/2/18 上午10:49
 */

namespace App\Controller\Suanfa;

use S\Suanfa\Node;

class Linkedlist extends \App\Controller\Base
{

    protected $verify = false;

    public function index($args)
    {

        $linked_list = new \S\Suanfa\LinkedList();

        $first = new Node();
        $first->value = '111';
        $first->next = null;

        $second = new Node();
        $second->value = '222';
        $second->next = null;
        $first->next = $second;

        $third = new Node();
        $third->value = '333';
        $third->next = null;
        $second->next = $third;

        dd($first);
    }

}