<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 19/11/4 下午5:51
 * Email: 18515831680@163.com
 * "redis队列消息监听"进程
 *
 * 启动脚本：php /www/wlz_phplib/queue/redis/taskRedis.php
 *
 */

namespace Queue\Redis;

use Queue\Redis\Redis;

require_once dirname(dirname(__DIR__)) . "/fun.php";

class Task {

    const DEFAULT_WAIT_SECONDS = 5; //无消息默认等待时间

    /**
     * 队列进程
     * @param $queue_name
     * @return bool
     * @throws \Exception
     */
    public function process($queue_name) {
        $redis = new \Queue\Redis\Redis();

        while (true) {
            try {
                $ret = $redis->pop($queue_name);
                if (false === $ret) {
                    throw new \Exception('no messages');
                }
                \Log::getInstance()->debug([__METHOD__, $queue_name, json_encode($ret), 'success']);

                //业务代码，如果业务代码执行异常可重新push进$queue_name队列
                /**
                 * code...
                 */
            } catch (\Exception $e) {
                \Log::getInstance()->debug([__METHOD__, $queue_name, 'no task, sleep 5s', $e->getCode(), $e->getMessage()]);
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
(new \Queue\Redis\Task())->process($queue_name);