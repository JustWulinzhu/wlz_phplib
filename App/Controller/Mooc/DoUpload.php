<?php

namespace App\Controller\Mooc;

use S\Log;
use S\Queue\Redis\Redis;
use S\Tools;

class doUpload extends \App\Controller\Base {

    public $verify = false;

    const OFFICE_FILE_PATH = '/data1/www/image';
    const VIDEO = '/tmp/video.txt';

    public function index($args)
    {
        if (! strpos($_FILES['GoodsPicture']['type'], 'mp4')) {
            echo "<script>alert('请上传MP4类型的视频文件');history.go(-1)</script>";
            return;
        }
        if ($_FILES['GoodsPicture']['size'] > 52428800 ) {
            echo "<script>alert('上传文件限制50m以内');history.go(-1)</script>";
            return;
        }
        $path = self::OFFICE_FILE_PATH . "/" . date("Ymd");
        Tools::mkdirIfNotExist($path);
        chmod($path, 0777);
        $file_name = md5(uniqid() . '-' . $_FILES['GoodsPicture']['name']);
        $file_path = $path ."/" . $file_name;
        move_uploaded_file($_FILES['GoodsPicture']['tmp_name'], $file_path);
        $url = APP_DOMAIN . ':8081/' . 'image/' . date("Ymd") . '/' . $file_name;
        file_put_contents(self::VIDEO, $url);
        chmod(self::VIDEO, 0777);
        echo "<script>alert('success');history.go(-1)</script>";
    }

}