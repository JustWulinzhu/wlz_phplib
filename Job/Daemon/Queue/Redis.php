<?php

namespace Job\Daemon\Queue;

use S\Log;

class Redis implements \Job\Base
{

    public static $sleep_seconds = 0;

    /**
     * @param $argv
     * @return mixed|void
     * @throws \Exception
     */
    public function exec($argv = null) {
        $task = new \S\Queue\Redis\Redis();
        $data = $task->pop('test');
        if ($data) {
            self::$sleep_seconds = 0;
            Log::getInstance()->debug(['redis_pop', $data]);
        } else {
            self::$sleep_seconds = 5;
        }

    }

}