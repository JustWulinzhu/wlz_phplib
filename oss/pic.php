<?php
/**
 * Created by PhpStorm.
 * Author: wulinzhu
 * Email: linzhu.wu@beebank.com
 * Date: 19/9/16 上午11:06
 */

require_once dirname(__DIR__) . '/' . 'fun.php';

class Pic {

    public function upload() {
        $files = Fun::scanDir('/www/log/');
        dd($files);
        $ret = Curl::request('http://39.105.182.40/wlz_phplib/oss/files.php?upload=1', 'POST', array('file' => new \CURLFile('/www/975BA281742AB3667F96001C977C8BE4.jpg')));
    }

}

(new Pic())->upload();