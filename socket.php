<?php
/**
 * Created by PhpStorm.
 * Author: wulinzhu
 * Email: linzhu.wu@beebank.com
 * Date: 19/4/28 下午4:31
 * Socket测试
 */

require_once __DIR__ . '/' . 'fun.php';

class Socket {

    //socket server
    public function server() {
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if (socket_bind($socket, '172.18.0.1', 8888) == false) {
            echo 'server bind fail:' . socket_strerror(socket_last_error());
            //这里的127.0.0.1是在本地主机测试，你如果有多台电脑，可以写IP地址
        }
        //监听套接流
        if (socket_listen($socket, 4) == false) {
            echo 'server listen fail:' . socket_strerror(socket_last_error());
        }
        do {
            //接收客户端传过来的信息
            $accept_resource = socket_accept($socket);
            //socket_accept的作用就是接受socket_bind()所绑定的主机发过来的套接流

            if ($accept_resource !== false) {
                //读取客户端传过来的资源，并转化为字符串
                $string = socket_read($accept_resource, 1024);
                //socket_read的作用就是读出socket_accept()的资源并把它转化为字符串

                echo 'server receive is :' . $string . PHP_EOL; //PHP_EOL为php的换行预定义常量
                if ($string != false) {
                    $return_client = 'server receive is : ' . $string.PHP_EOL;
                    //向socket_accept的套接流写入信息，也就是回馈信息给socket_bind()所绑定的主机客户端
                    socket_write($accept_resource, $return_client, strlen($return_client));
                    //socket_write的作用是向socket_create的套接流写入信息，或者向socket_accept的套接流写入信息
                } else {
                    echo 'socket_read is fail';
                }
                //socket_close的作用是关闭socket_create()或者socket_accept()所建立的套接流
                socket_close($accept_resource);
            }
        } while (true);
        socket_close($socket);
    }

    //socket client
    public function client() {
        //创建一个socket套接流
        $socket = socket_create(AF_INET,SOCK_STREAM, SOL_TCP);
        /****************设置socket连接选项，这两个步骤你可以省略*************/
        //接收套接流的最大超时时间1秒，后面是微秒单位超时时间，设置为零，表示不管它
        socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array("sec" => 1, "usec" => 0));
        //发送套接流的最大超时时间为6秒
        socket_set_option($socket, SOL_SOCKET, SO_SNDTIMEO, array("sec" => 6, "usec" => 0));
        /****************设置socket连接选项，这两个步骤你可以省略*************/

        //连接服务端的套接流，这一步就是使客户端与服务器端的套接流建立联系
        if (socket_connect($socket, '172.18.0.1', 8888) == false) {
            echo 'connect fail massage:' . socket_strerror(socket_last_error());
        } else {
            $message = 'l love you 我爱你 socket';
            //向服务端写入字符串信息
            if (socket_write($socket, $message, strlen($message)) == false) {
                echo 'fail to write' . socket_strerror(socket_last_error());
            } else {
                echo 'client write success' . PHP_EOL;
                //读取服务端返回来的套接流信息
                while ($callback = socket_read($socket, 1024)) {
                    echo 'server return message is:' . PHP_EOL . $callback;
                }
            }
        }
        socket_close($socket); //工作完毕，关闭套接流
    }

}

$socket = new Socket();

if (Fun::isCli()) {
    if ($argv[1] == 'server') {
        $socket->server();
    } else {
        $socket->client();
    }
} else {
    if ($_GET['env'] == 'server') {
        $socket->server();
    } else {
        $socket->client();
    }
}

