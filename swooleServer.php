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
        $this->server = new swoole_server("0.0.0.0", 9501);
        $this->server->set(array(
            'worker_num'        => 8,
            'task_worker_num'   => 2, //设置启动task进程
        ));

        $this->server->on('Start', 'onStart');
        $this->server->on('Connect', 'onConnect');
        $this->server->on('Receive', 'onReceive');
        $this->server->on('Close', 'onClose');
        $this->server->on('Task', 'onTask');
        $this->server->on('Finish', 'onFinish');

        $this->server->start();
    }

    /**
     * 投递任务到Task Worker, 类似队列,push一个任务到队列中异步处理
     * @param $task_id
     * @param $from_id
     * @param $data
     * @return string
     */
    public function onTask($task_id, $from_id, $data) {
        echo "This Task {$task_id} from Worker {$from_id}\n";
        echo "Data: {$data}\n";
        for($i = 0 ; $i < 3 ; $i++ ) {
            sleep(1);
            echo "Task {$task_id} Handle {$i} times...\n";
        }
        $fd = json_decode( $data , true )['fd'];
        $this->server->send( $fd , "Data in Task {$task_id}");
        return "Task {$task_id}'s result";
    }

    /**
     * @param $task_id int 任务id
     * @param $data string 任务返回的数据
     */
    public function onFinish($task_id, $data) {
        echo "Task {$task_id} finish\n";
        echo "Result: {$data}\n";
    }


    public function onStart() {
        echo "Start\n";
    }

    /**
     * @param $fd
     * @param $from_id
     */
    public function onConnect($fd, $from_id) {
        $this->server->send($fd, "Hello {$fd}!");
    }

    /**
     * @param $fd
     * @param $from_id
     * @param $data
     */
    public function onReceive($fd, $from_id, $data) {
        echo "Get Message From Client {$fd}:{$data}\n";
        // send a task to task worker.
        $param = array('fd' => $fd);
        // start a task
        $this->server->task( json_encode( $param ) );

        echo "Continue Handle Worker\n";
    }

    /**
     * @param $fd
     * @param $from_id
     */
    public function onClose($fd, $from_id) {
        echo "Client {$fd} close connection\n";
    }

}

$server = new SwooleServer();