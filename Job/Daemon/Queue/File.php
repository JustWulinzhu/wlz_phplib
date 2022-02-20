<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 20/1/16 上午11:20
 * Email: 18515831680@163.com
 *
 * 分片上传，可用于大文件上传
 *
 */

namespace Job\Daemon\Queue;

use S\Log;
use S\Oss\Files;
use S\Oss\Oss;

class File implements \Job\Base
{

    public static $sleep_seconds;

    /**
     * @param $argv
     * @return mixed|void
     * @throws \Exception
     */
    public function exec($argv = null)
    {
        $oss = new Oss();
        $task = new \S\Queue\Redis\Redis();

        $data = $task->pop(Files::QUEUE_LARGE_FILE_UPLOAD_KEY);
        if ($data) {
            self::$sleep_seconds = 0;
            $data = json_decode($data, true);
            $oss->partUpload($data['bucket'], $data['local_file_path'], $data['file_path'], $data['part_size']);
        } else {
            self::$sleep_seconds = 10;
        }

    }

}