<?php

namespace App\Controller\Mooc;

use S\Log;
use S\Param;
use S\Queue\Redis\Redis;
use S\Tools;

class Dorecord extends \App\Controller\Base {

    public $verify = false;

    public function index($args)
    {
        Log::getInstance()->debug(['video_record', json_encode(Param::request())]);
        $param = Param::request();
        $total_time = intval($param["duration"]);
        $current_time = intval($param["currentTime"]);

        $ip = Tools::getCliIp();
        $content = @file_get_contents("/tmp/video.txt");
        $name = pathinfo($content)['basename'];
        $file = "/tmp/video_time_record_{$ip}_{$name}.txt";
        $record = @file_get_contents($file);
        if ($record) {
            $record_arr = explode(" ", $record);
            $old_current_time = $record_arr[1];
            $new_current_time = $old_current_time + $current_time;
            $content = $total_time . ' ' . $new_current_time;
            file_put_contents($file, $content);
        } else {
            $content = $total_time . ' ' . $current_time;
            file_put_contents($file, $content);
            chmod($file, 0777);
        }
    }

}