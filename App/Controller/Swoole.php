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
     * @throws \SmartyException
     */
    public function index($arr) {
        $this->smarty->display("Swoole/Swoole.html");
    }

}