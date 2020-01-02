<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 20/1/2 上午12:37
 * Email: 18515831680@163.com
 */

namespace App\Controller;

class Swoole extends \App\Controller\Base {

    /**
     * @param $arr
     */
    public function index($arr) {
        $this->smarty->assign('data', ['a' => '1']);
        $this->smarty->display("index.html");
    }

}