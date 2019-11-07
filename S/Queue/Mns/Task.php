<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 19/11/3 下午5:15
 * Email: 18515831680@163.com
 *
 * "mns消息监听"进程
 *
 */

namespace S\Queue\Mns;

use S\Queue\Mns\Mns;
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
        $mns = new \S\Queue\Mns\Mns();

        while (true) {
            try {
                $ret = $mns->pop($queue_name);
                if (true === $ret['is_success']) {
                    //执行业务
                    Log::getInstance()->debug([__METHOD__, $queue_name, $ret['body'], 'success']);
                } else {
                    throw new \Exception($queue_name . ' pop failed', $ret['status']);
                }
            } catch (\Throwable $e) {
                Log::getInstance()->debug([__METHOD__, $queue_name, 'no task, sleep 5s', $e->getCode(), $e->getMessage()]);
                sleep(self::DEFAULT_WAIT_SECONDS);
            }
        }

        return true;
    }

}