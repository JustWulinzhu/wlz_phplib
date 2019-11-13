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

use S\Log;

class RedisQueue implements \Job\Base
{

    /**
     * @param null $argv
     * @throws \Exception
     */
    public function exec($argv = null)
    {
        $redis_task = new \S\Queue\Redis\Task();
        $ret = $redis_task->process($argv[0]);
        if ($ret) {
            Log::getInstance()->debug(['task end', date('Ymd H:i:s', time())]);
        }
    }

}