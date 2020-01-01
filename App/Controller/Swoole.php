<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 20/1/2 上午12:37
 * Email: 18515831680@163.com
 */

namespace App\Controller;

class Swoole extends \App\Controller\Base {

    public function index($arr) {
        //自定义模板目录（不介意自定义）
        $this->smarty->template_dir = APP_ROOT_PATH . '/App/View';
        //自定义编译目录（不介意自定义）
        $this->smarty->compile_dir = APP_ROOT_PATH . '/Ext/Smarty/templates_c';
        //自定义变量目录（不介意自定义）
        $this->smarty->config_dir = APP_ROOT_PATH . '/Ext/Smarty/configs';
        //缓存目录（不介意自定义）
        $this->smarty->cache_dir = APP_ROOT_PATH . '/Ext/Smarty/cache';

        $this->smarty->display("index.html");
    }

}