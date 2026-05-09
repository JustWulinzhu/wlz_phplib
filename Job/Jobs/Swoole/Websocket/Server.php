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

    public function exec($argv = null) {
        // 创建 WebSocket 服务器，监听 0.0.0.0:9502
        $server = new \Swoole\WebSocket\Server("0.0.0.0", 9502);

        // 设置运行参数
        $server->set([
            'worker_num' => 2,              // 2 个 Worker 进程
            'daemonize' => false,          // 非守护模式
            'log_file' => './websocket.log',
        ]);

        // 当客户端建立连接时触发
        $server->on('open', function ($server, $request) {
            echo "客户端 {$request->fd} 已连接\n";
            // 可以在此处向所有在线客户端广播欢迎消息，但这里仅打印日志
        });

        // 当收到客户端发来的消息时触发
        $server->on('message', function ($server, $frame) {
            echo "收到来自客户端 {$frame->fd} 的消息: {$frame->data}\n";

            // 向所有客户端广播此消息（包括发送者自己）
            foreach ($server->connections as $fd) {
                // 检查连接是否仍然有效（可能是 WebSocket 连接）
                if ($server->isEstablished($fd)) {
                    $server->push($fd, $frame->data);
                }
            }
        });

        // 当客户端断开连接时触发
        $server->on('close', function ($server, $fd) {
            echo "客户端 {$fd} 已断开\n";
        });

        // 启动服务器
        $server->start();
    }

}