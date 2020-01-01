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
        $smarty = $this->smarty;
        //自定义模板目录（不介意自定义）
        $smarty->template_dir=APP_ROOT_PATH.'/App/View';
        //自定义编译目录（不介意自定义）
        $smarty->compile_dir=APP_ROOT_PATH.'/Smarty/templates_c';
        //自定义变量目录（不介意自定义）
        $smarty->config_dir=APP_ROOT_PATH.'/Smarty/configs';
        //缓存目录（不介意自定义）
        $smarty->cache_dir=APP_ROOT_PATH.'/Smarty/cache';

        $smarty->display("index.html");
    }

}