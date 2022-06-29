<?php

namespace App\Controller\Mooc;

use S\Log;
use S\Param;
use S\Queue\Redis\Redis;

class Dorecord extends \App\Controller\Base {

    public $verify = false;

    const PASS_FILE = '/tmp/pass.txt';

    public function index($args)
    {
        session_start();
        Log::getInstance()->debug(['xxoo', json_encode(Param::request())]);
    }

}