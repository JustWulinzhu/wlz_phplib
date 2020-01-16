<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 20/1/16 上午11:20
 * Email: 18515831680@163.com
 *
 * 大文件上传
 *
 */

namespace Job\Daemon\Queue;

use S\Log;
use S\Oss\Files;
use S\Oss\Oss;

class File implements \Job\Base
{

    public static $sleep_seconds = 0;

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
            $ret = $oss->partUpload($data['bucket'], $data['local_file_path'], $data['file_path'], $data['part_size']);
            Log::getInstance()->debug(['large file upload ret', json_encode($data), $ret]);
        } else {
            self::$sleep_seconds = 10;
        }

    }

}