<?php

namespace App\Controller\Mooc;

use S\Log;
use S\Queue\Redis\Redis;

class ChangePwd extends \App\Controller\Base {

    public $verify = false;

    public function index($args)
    {
        $this->smarty->display("Mooc/ChangePwd.html");
    }

}