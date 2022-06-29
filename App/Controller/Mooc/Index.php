<?php

namespace App\Controller\Mooc;

use S\Log;
use S\Queue\Redis\Redis;

class Index extends \App\Controller\Base {

    public $verify = false;

    public function index($args)
    {
        session_start();
        if (! isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
            exit(header("Location:" . APP_DOMAIN . "/mooc/login"));
        }
        $this->smarty->display("Mooc/index.html");
    }

}