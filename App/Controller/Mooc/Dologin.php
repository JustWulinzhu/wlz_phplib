<?php

namespace App\Controller\Mooc;

use S\Log;
use S\Param;
use S\Queue\Redis\Redis;

class Dologin extends \App\Controller\Base {

    public $verify = false;

    const PASS_FILE = '/tmp/pass.txt';

    public function index($args)
    {
        session_start();
        $username = trim(Param::request("username"));
        $password = trim(Param::request("password"));

        if (! file_exists(self::PASS_FILE)) {
            touch(self::PASS_FILE);
            chmod(self::PASS_FILE, 0777);
            file_put_contents(self::PASS_FILE, 'admin 123456');
        }
        $content = file_get_contents(self::PASS_FILE);
        $content_arr = explode(" ", $content);
        $old_username = $content_arr[0];
        $old_password = $content_arr[1];
        if ($old_username != $username) {
            throw new \Exception("用户不存在");
        }
        if ($old_password != $password) {
            throw new \Exception("密码错误");
        }
        $_SESSION['is_login'] = true;
    }

}