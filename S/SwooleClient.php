<?php
/**
 * Created by PhpStorm.
 * Author: wulinzhu
 * Email: linzhu.wu@beebank.com
 * Date: 19/5/10 下午2:55
 */

namespace S;

class SwooleClient {

    private $client;

    public function __construct() {
        $this->client = new swoole_client(SWOOLE_SOCK_TCP | SWOOLE_KEEP);
    }

    public function connect() {
        if(! $this->client->connect("127.0.0.1", 9501 , 1)) {
            echo "Error: {$this->client->errMsg}[{$this->client->errCode}]\n";
        }
        $message = $this->client->recv();
        echo "Get Message From Server:{$message}\n";

        fwrite(STDOUT, "请输入消息：");
        $msg = trim(fgets(STDIN));
        $this->client->send($msg);
    }

}

$client = new SwooleClient();
$client->connect();