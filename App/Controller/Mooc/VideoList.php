<?php

namespace App\Controller\Mooc;

use S\Log;
use S\Queue\Redis\Redis;

class VideoList extends \App\Controller\Base {

    public $verify = false;

    public function index($args)
    {
        $content = file_get_contents("/tmp/video.txt");
        define("IMAGE_URL", $content);
        $this->smarty->display("Mooc/VideoList.html");
    }

}