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
        if (! file_exists($file)) {
            touch($file);
            chmod($file, 0777);
        } else {
            $record = @file_get_contents($file);
            if ($record) {
                $record_arr = explode(" ", $record);
                $old_total_time = $record_arr[0];
                $old_current_time = $record_arr[1];
                if ($old_current_time < $old_total_time /2) {
                    $content = $total_time . ' ' . $current_time;
                    file_put_contents($file, $content);
                }
            } else {
                $content = $total_time . ' ' . $current_time;
                file_put_contents($file, $content);
            }

        }
    }

}