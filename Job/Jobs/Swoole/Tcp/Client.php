<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 19/12/31 下午5:14
 * Email: 18515831680@163.com
 *
 * swoole tcp client
 *
 */

namespace Job\Jobs\Tcp\Swoole;

class Client implements \Job\Base
{

    private $client;

    /**
     * @param null $argv
     * @return mixed|void
     */
    public function exec($argv = null)
    {
        $this->client = new \Swoole\Async\Client(SWOOLE_SOCK_TCP);

        $this->client->on("connect", function($cli) {
            $cli->send("hello world\n");
        });
        $this->client->on("receive", function($cli, $data){
            echo "received: {$data}\n";
        });
        $this->client->on("error", function($cli){
            echo "connect failed\n";
        });
        $this->client->on("close", function($cli){
            echo "connection close\n";
        });

        $this->client->connect("127.0.0.1", 9501, 1);
    }

}