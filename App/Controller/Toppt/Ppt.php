<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 20/3/25 下午2:19
 * Email: 18515831680@163.com
 *
 * ppt首页
 *
 */

namespace App\Controller\Toppt;

class Ppt extends \App\Controller\Base
{

    protected $verify = false;

    /**
     * @param $arr
     */
    public function index($arr)
    {
        $this->smarty->assign("APP_DOMAIN", APP_DOMAIN);
        $this->smarty->display("Toppt/Index.html");
    }

}