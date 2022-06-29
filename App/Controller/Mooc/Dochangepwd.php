<?php

namespace App\Controller\Mooc;

use S\Log;
use S\Param;
use S\Queue\Redis\Redis;

class Dochangepwd extends \App\Controller\Base {

    public $verify = false;

    const PASS_FILE = '/tmp/pass.txt';

    public function index($args)
    {
        $password = trim(Param::request("password"));
        $confirm_password = trim(Param::request("confirm_password"));
        if ($password != $confirm_password) {
            throw new \Exception("两次密码输入不一致");
        }
        if (! file_exists(self::PASS_FILE)) {
            touch(self::PASS_FILE);
            chmod(self::PASS_FILE, 0777);
        }
        $content = file_get_contents(self::PASS_FILE);
        $content_arr = explode(" ", $content);
        $old_username = $content_arr[0];
        $new_password = $password;
        file_put_contents(self::PASS_FILE, $old_username . ' ' . $new_password);

        return 'success';
    }

}