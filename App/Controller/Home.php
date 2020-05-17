<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 20/4/21 下午2:08
 * Email: 18515831680@163.com
 *
 * 网站首页
 *
 */

namespace App\Controller;

class Home extends Base {

    protected $verify = false;

    public function index() {
        $this->smarty->assign('APP_ROOT_PATH', APP_ROOT_PATH);
        $this->smarty->display('Home/Index.html');
    }

}