<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 19/12/03 下午4:37
 * Email: 18515831680@163.com
 */

namespace Job\Daemon;

class Master implements \Job\Base
{

    /**
     * @param $argv
     * @return mixed|void
     * @throws \Exception
     */
    public function exec($argv = null) {
        $action = $argv[0];

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
                throw new \Exception('action error');
        }
    }

    private function _setConfig() {
        $thread = new \S\Daemon\Thread();
        $thread->setDaemonConfig("\\Job\\Daemon\\Queue\\Redis", 3);
    }

}