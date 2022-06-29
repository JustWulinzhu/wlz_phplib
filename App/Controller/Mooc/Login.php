<?php

namespace App\Controller\Mooc;

use S\Log;
use S\Queue\Redis\Redis;

class Login extends \App\Controller\Base {

    public $verify = false;

    public function index($args)
    {
        session_start();
        unset($_SESSION['is_login']);
        $this->smarty->display("Mooc/Login.html");
    }

}