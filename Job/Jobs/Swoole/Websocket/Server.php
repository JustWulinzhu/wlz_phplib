<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 20/1/1 下午7:31
 * Email: 18515831680@163.com
 *
 * swoole websocket server
 *
 */

namespace Job\Jobs\Swoole\Websocket;

use S\Log;

class Server implements \Job\Base {

    private $server;

    public function exec($argv = null) {
        $this->server = new \swoole_websocket_server("0.0.0.0", 9502);

        //进程配置
        $this->server->set([
            'worker_num' => 5, //启动进程数
            'daemonize' => true, //是否为守护进程
        ]);

        //监听客户端连接
        $this->server->on('open', function($server, $req) {
            Log::getInstance()->debug(['Client id ' . $req->fd]);
        });

        //监听客户端消息
        $this->server->on('message', function($server, $frame) {
            foreach ($server->connections as $fd) {
                Log::getInstance()->debug(['message', $fd, $frame->data]);
                $server->push($fd, $frame->data);
            }
        });

        //监听swoole服务关闭
        $this->server->on('close', function($server, $fd) {
            Log::getInstance()->debug(['Server closed ' . $fd]);
        });

        //启动服务
        $this->server->start();
    }

}