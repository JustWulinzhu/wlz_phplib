<?php
/**
 * Created by PhpStorm.
 * Author: wulinzhu
 * Email: linzhu.wu@beebank.com
 * Date: 21/7/20 下午2:22
 */

namespace App\Controller\Home;

class Index extends \App\Controller\Base
{

    protected $verify = false;

    /**
     * @param $arr
     */
    public function index($arr)
    {
        $this->smarty->assign("APP_ROOT_PATH", APP_ROOT_PATH);
        $this->smarty->display("Home/Index.html");
    }

}