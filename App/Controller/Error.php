<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 20/4/6 下午5:45
 * Email: 18515831680@163.com
 */

namespace App\Controller;

class Error extends \App\Controller\Base {

    protected $verify = false;

    /**
     * @param $args
     */
    public function index($args) {
        $this->smarty->assign("APP_DOMAIN", APP_DOMAIN);
        $this->smarty->display("Error/404.html");
    }

}