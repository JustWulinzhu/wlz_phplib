<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 19/11/3 下午5:15
 * Email: 18515831680@163.com
 * mns消息监听进程
 *
 * Linux后台执行命令 末尾加&
 *
 */

require_once dirname(dirname(__DIR__)) . "/fun.php";

class Task {

    const DEFAULT_EXEC_TIMES = 10000;

    /**
     * 队列进程
     * @param $queue_name
     * @return bool
     * @throws Exception
     */
    public function process($queue_name) {
        $mns = new Mns();

        $i = 0;
        while ($i < self::DEFAULT_EXEC_TIMES) {
            try {
                $ret = $mns->pop($queue_name);
                if ($ret) {
                    //执行业务
                    Log::getInstance()->debug([__CLASS__, __METHOD__, $queue_name, json_encode($ret), 'success']);
                }
            } catch (\Throwable $e) {
                Log::getInstance()->debug([__CLASS__, __METHOD__, $queue_name, $i, 'no task, sleep 5s', $e->getMessage(), $e->getCode()]);
                sleep(5);
            }

            $i++;
            if ($i >= self::DEFAULT_EXEC_TIMES) {
                $i = 0;
                Log::getInstance()->debug([__CLASS__, __METHOD__, 'reset $i']);
            }
        }

        return true;
    }

}

$queue_name = $argv[1];
if (empty($queue_name)) {
    die('queue_name argument cannot be empty');
}
(new Task())->process($queue_name);
