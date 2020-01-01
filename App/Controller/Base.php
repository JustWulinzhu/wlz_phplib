<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 20/1/2 上午1:33
 * Email: 18515831680@163.com
 *
 * smarty 模板
 *
 */

namespace App\Controller;

class Base {

    protected $smarty;

    /**
     * smarty construct
     * Base constructor.
     */
    public function __construct()
    {
        $this->smarty = new \Smarty();
    }

}