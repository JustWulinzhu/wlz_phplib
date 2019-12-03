<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 19/11/15 下午4:37
 * Email: 18515831680@163.com
 */

namespace Job\Daemon;

use S\Log;

class Master implements \Job\Base
{

    /**
     * @param $argv
     * @return mixed|void
     * @throws \Exception
     */
    public function exec($argv) {
        $action = $argv[0];

        $thread = new \Job\Daemon\Thread();
        switch ($action) {
            case "start" :
                $this->_setConfig();
                $thread->run();
                break;
            case "reload" :
                $this->_setConfig();
                $thread->killAll();
                $thread->run();
                break;
            case "stop" :
                $thread->killAll();
                break;
            default:
                throw new \Exception('action error');
        }
    }

    private function _setConfig() {
        $thread = new \Job\Daemon\Thread();
        $thread->setDaemonConfig("\\Job\\Daemon\\Test", 3);
        $thread->setDaemonConfig("\\Job\\Daemon\\Test2", 2);
    }

}