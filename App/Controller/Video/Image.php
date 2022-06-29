<?php

namespace App\Controller\Video;

use S\Log;
use S\Queue\Redis\Redis;

class Image extends \App\Controller\Base {

    public $verify = false;

    public function index($args)
    {
        $content = file_get_contents("/tmp/video.txt");
        define("IMAGE_URL", $content);
        define("DES", "TEST");
        $this->smarty->display("Video/Index.html");
    }

}