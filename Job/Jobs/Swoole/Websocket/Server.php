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

class Server implements \Job\Base {

    private $server;

    public function exec($argv = null) {
        $this->server = new \swoole_websocket_server("127.0.0.1", 9502);
        $this->server->on('open', function($server, $req) {
            echo "connection open: {$req->fd}\n";
        });

        $this->server->on('message', function($server, $frame) {
            echo "received message: {$frame->data}\n";
            $server->push($frame->fd, json_encode(["hello", "world"]));
        });

        $this->server->on('close', function($server, $fd) {
            echo "connection close: {$fd}\n";
        });

        $this->server->start();
    }

}