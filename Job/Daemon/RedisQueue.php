<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 19/11/7 下午6:45
 * Email: 18515831680@163.com
 *
 * 启动脚本：php /www/wlz_phplib/Job/Job.php Daemon_RedisQueue &
 *
 */

namespace Job\Daemon;

class RedisQueue
{

    /**
     * @param null $argv
     * @throws \Exception
     */
    public function exec($argv = null)
    {
        $redis_task = new \S\Queue\Redis\Task();
        $redis_task->process($argv);
    }

}

(new RedisQueue())->exec('test');