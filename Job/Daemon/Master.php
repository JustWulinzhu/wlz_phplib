<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 19/12/03 下午4:37
 * Email: 18515831680@163.com
 *
 * 守护进程daemon
 * 1. 启动一个主进程，用来监控配置信息
 * 2. 根据配置信息启动相应数量的子进程进行业务处理
 * 3. 一旦发现配置信息发生改变，杀死之前的进程重写创建
 *
 * 执行command: php /www/wlz_phplib/Job/Job.php Job_Daemon_Master start|stop|reload
 *
 */

namespace Job\Daemon;

class Master implements \Job\Base
{

    private function _setConfig() {
        $thread = new \S\Daemon\Thread();
        $thread->setDaemonConfig("\\Job\\Daemon\\Queue\\Redis", 2);
        $thread->setDaemonConfig("\\Job\\Daemon\\Queue\\File", 5);
    }

    /**
     * @param $argv
     * @return mixed|void
     * @throws \Exception
     */
    public function exec($argv = null) {
        $action = isset($argv[0]) ? $argv[0] : '';

        $thread = new \S\Daemon\Thread();
        switch ($action) {
            case "start" :
                $this->_setConfig();
                $thread->run();
                break;
            case "reload" :
                $thread->killAll();
                $this->_setConfig();
                $thread->run();
                break;
            case "stop" :
                $thread->killAll();
                break;
            default:
                throw new \Exception('action can not be empty');
        }
    }

}