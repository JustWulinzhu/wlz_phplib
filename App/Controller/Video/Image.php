<?php

namespace App\Controller\Video;

use S\Log;
use S\Queue\Redis\Redis;
use S\Tools;

class Image extends \App\Controller\Base {

    public $verify = false;

    public function index($args)
    {
        $ip = Tools::getCliIp();
        Log::getInstance()->debug([$ip]);
        $content = @file_get_contents("/tmp/video.txt");

        $flag = 'unlooked';
        if (file_exists("/tmp/video_record_{$ip}.txt")) {
            $video_record = file_get_contents("/tmp/video_record_{$ip}.txt");
            if ($content == $video_record) {
                $flag = 'looked';
            } else {
                file_put_contents("/tmp/video_record_{$ip}.txt", $content);
            }
        } else {
            file_put_contents("/tmp/video_record_{$ip}.txt", $content);
            chmod("/tmp/video_record_{$ip}.txt", 0777);
        }

        define("IMAGE_URL", $content);
        define("DES", "TEST");
        define("IS_LOOKED", $flag);
        $this->smarty->display("Video/Index.html");
    }

}