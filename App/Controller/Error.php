<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 20/4/6 下午5:45
 * Email: 18515831680@163.com
 */

namespace App\Controller;

class Error extends \App\Controller\Base {

    /**
     *  404错误页面
     */
    public function notFound404() {
        $this->smarty->assign("APP_HOST", APP_HOST);
        $this->smarty->display("Error/404.html");
    }

}