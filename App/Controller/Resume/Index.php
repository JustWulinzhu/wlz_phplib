<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 20/8/20 上午11:05
 * Email: 18515831680@163.com
 */

namespace App\Controller\Resume;

use App\Controller\Base;

class Index extends Base {

    public $verify = false;

    public function index($arr) {
        $this->smarty->display("Resume/Index.html");
    }

}