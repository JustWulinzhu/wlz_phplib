<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 19/11/4 下午5:51
 * Email: 18515831680@163.com
 *
 * "redis消息队列监听"进程
 *
 * 启动脚本：php /www/wlz_phplib/queue/redis/task.php $queue_name &
 *
 */

namespace S\Queue\Redis;

use S\Log;

class Task {

    const DEFAULT_WAIT_SECONDS = 5; //无消息默认等待时间

    /**
     * 队列进程
     * @param $queue_name
     * @return bool
     * @throws \Exception
     */
    public function process($queue_name) {
        $redis = new \S\Queue\Redis\Redis();

        while (true) {
            try {
                $ret = $redis->pop($queue_name);
                if (false === $ret) {
                    throw new \Exception('no messages');
                }
                Log::getInstance()->debug([__METHOD__, $queue_name, $ret, 'success']);

                //业务代码，如果业务代码执行异常可重新push进$queue_name队列
                usleep(500000);
                /**
                 * code...
                 */
            } catch (\Exception $e) {
                Log::getInstance()->debug([__METHOD__, $queue_name, 'no task, sleep 5s', $e->getCode(), $e->getMessage()]);
                sleep(self::DEFAULT_WAIT_SECONDS);
            }
        }

        return true;
    }

}

$queue_name = $argv[1];
if (empty($queue_name)) {
    die('queue_name argument cannot be empty');
}
(new \S\Queue\Redis\Task())->process($queue_name);