<?php

namespace App\Controller\Video;

use S\Log;
use S\Queue\Redis\Redis;

class Image extends \App\Controller\Base {

    public $verify = false;

    public function index($args)
    {
        define("MP4_URL", "http://wlfeng.vip:8081/image/test.mp4");
        define("IMAGE_URL", "http://www.wlfeng.vip:8081/image/gou.png");
        define("DES", "TEST");
        $this->smarty->display("Video/Index.html");
    }

}