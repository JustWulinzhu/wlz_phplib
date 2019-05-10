<?php
/**
 * Created by PhpStorm.
 * Author: wulinzhu
 * Email: linzhu.wu@beebank.com
 * Date: 19/5/10 下午2:32
 */

class SwooleServer {

    private $server;

    public function __construct() {
        $this->server = new swoole_server("127.0.0.1", 9501);
        $this->server->set(array(
            'worker_num'        => 8,
            'daemonize'         => false,
            'max_request'       => 10000,
            'dispatch_mode'     => 2,
            'debug_mode'        => 1,
            'task_worker_num'   => 0,
        ));

        $this->server->on('Start', array($this, 'onStart'));
        $this->server->on('Connect', array($this, 'onConnect'));
        $this->server->on('Receive', array($this, 'onReceive'));
        $this->server->on('Close', array($this, 'onClose'));

        $this->server->start();
    }

    public function onStart($server) {
        echo "Start\n";
    }

    public function onConnect($server, $fd, $from_id) {
        $server->send($fd, "Hello {$fd}!");
    }

    public function onReceive(swoole_server $server, $fd, $from_id, $data) {
        echo "Get Message From Client {$fd}:{$data}\n";
    }

    public function onClose($server, $fd, $from_id) {
        echo "Client {$fd} close connection\n";
    }

    public function onTask($server, $task_id, $from_id, $data) {
        $server->send($fd, "Hello {$fd}!");
    }

    public function onFinish($server, $task_id, $data) {
        echo "Task {$task_id} finish\n";
        echo "Result: {$data}\n";
    }

}

$server = new SwooleServer();