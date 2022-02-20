<?php
/**
 * Created by PhpStorm.
 * Author: wulinzhu
 * Email: linzhu.wu@beebank.com
 * Date: 21/7/8 下午3:49
 */

namespace App\Controller\Load;

use S\Log;

class Host2 extends \App\Controller\Base
{

    protected $verify = false;

    /**
     * @param null $arr
     * @throws \Exception
     */
    public function index($arr = null)
    {
        Log::getInstance()->debug([__METHOD__, "Host2"]);
    }

}