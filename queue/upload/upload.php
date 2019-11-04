<?php
/**
 * Created by PhpStorm.
 * Author: wulinzhu
 * Email: linzhu.wu@beebank.com
 * Date: 19/9/24 下午4:28
 */
require_once dirname(dirname(__DIR__)) . "/fun.php";

class Upload {

    public function push() {
        $queue = new Redis();
        $files = Fun::scanDir('/iphone/20190917-031758');
        foreach ($files as $file) {
            $ret = $queue->push('pic_upload', $file);
            Log::getInstance()->debug(array('pic_upload', $ret ? 1 : 2));
        }
    }

}

(new Upload())->push();