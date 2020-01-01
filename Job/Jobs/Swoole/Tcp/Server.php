<?php
/**
 * swoole tcp server
 */

namespace Job\Jobs\Tcp\Swoole;

class Server implements \Job\Base {

    private $server;

    /**
     * @param null $argv
     * @return mixed|void
     */
    public function exec($argv = null) {
        $this->server = new \swoole_server("0.0.0.0", 9501);

        $this->server->on('connect', function ($server, $fd) {
            echo "connect xxx\n";
        });
        $this->server->on('receive', function ($server, $fd, $reactor_id, $data) {
            $this->server->send($fd, "server主动推送 Swoole: {$data}");
            $this->server->close($fd);
        });
        $this->server->on('close', function ($server, $fd) {
            echo "closed xxx {$fd} \n";
        });

        $this->server->start();
    }

}