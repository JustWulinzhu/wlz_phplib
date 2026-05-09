<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 20/1/2 上午12:37
 * Email: 18515831680@163.com
 */

namespace App\Controller;

class Swoole extends \App\Controller\Base {

    protected $verify = false;

    /**
     * @param $arr
     * @throws \SmartyException
     */
    public function index($arr) {
// 1. 创建 WebSocket 服务器，监听 0.0.0.0:9501 端口
// 0.0.0.0 表示允许所有IP访问，9501是自定义端口（需确保未被占用）
        $server = new \Swoole\WebSocket\Server('0.0.0.0', 9501);

// 2. 配置服务器（新手先了解核心配置）
        $server->set([
            'worker_num' => 2, // Worker进程数，建议设为 CPU 核心数（比如2核就设2）
            'daemonize' => false, // 是否以守护进程运行（false：前台运行，方便看日志；true：后台运行）
            'log_file' => './swoole_websocket.log', // 日志文件路径，方便排查问题
        ]);

// 3. 监听「服务器启动」事件（可选，用于提示启动成功）
        $server->on('Start', function ($server) {
            echo "WebSocket 服务器启动成功：ws://0.0.0.0:9501\n";
        });

// 4. 监听「客户端连接成功」事件
        $server->on('Open', function ($server, $request) {
            // $request->fd 是客户端的唯一标识（文件描述符），每个连接的fd不同
            echo "客户端 {$request->fd} 已连接\n";
            // 主动给刚连接的客户端发欢迎消息
            $server->push($request->fd, "你好，欢迎连接聊天服务器！你的ID：{$request->fd}");
        });

// 5. 监听「收到客户端消息」事件（核心业务逻辑）
        $server->on('Message', function ($server, $frame) {
            // $frame->fd：发送消息的客户端fd
            // $frame->data：客户端发送的消息内容
            echo "收到客户端 {$frame->fd} 的消息：{$frame->data}\n";

            // 【核心】消息广播：把当前客户端的消息推送给所有已连接的客户端
            foreach ($server->connections as $fd) {
                // 先判断fd对应的连接是否是WebSocket连接（避免推送给非WebSocket客户端）
                if ($server->isEstablished($fd)) {
                    $server->push($fd, "用户 {$frame->fd} 说：{$frame->data}");
                }
            }
        });

// 6. 监听「客户端断开连接」事件
        $server->on('Close', function ($server, $fd) {
            echo "客户端 {$fd} 已断开连接\n";
        });

// 7. 启动服务器（关键：必须最后调用）
        $server->start();
    }

}