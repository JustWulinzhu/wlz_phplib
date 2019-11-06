<?php
/**
 * Created by PhpStorm.
 * Author: wulinzhu
 * Email: linzhu.wu@beebank.com
 * Date: 19/9/24 下午4:29
 */
require_once dirname(dirname(__DIR__)) . "/fun.php";

$queue = new \S\Queue\Redis\Redis();
$data = $queue->pop('pic_upload');
if ($data) {
    \S\Log::getInstance()->debug(array('pop_data', $data));
    if (in_array(\S\Fun::getExtendName($data), ['pic', 'jpeg', 'jpg', 'png', 'mp4'])) {
        $ret = \S\Curl::request(
            'http://39.105.182.40/wlz_phplib/oss/files.php?upload=1',
            'POST',
            array('file' => new \CURLFile('/iphone/20190917-031758/' . $data))
        );
        \S\Log::getInstance()->debug([$data, $ret]);
    }
}