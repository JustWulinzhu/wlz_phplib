<?php

namespace App\Controller\Mooc;

use S\Log;
use S\Queue\Redis\Redis;

class DoLogin extends \App\Controller\Base {

    public $verify = false;

    public function index($args)
    {
        $this->response_format = self::RESPONSE_FORMAT_JSON;
        dd($_POST);
    }

}