<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 19/10/29 上午11:18
 * Email: 18515831680@163.com
 * 生成aes加密所需iv参数，固定长度16字节
 */

if (\S\Fun::isCli()) {
    $iv = substr(md5(microtime(true)), 0, 16);
    \S\Log::getInstance()->debug(array('create_iv', $iv));
    echo $iv . "\n";
} else {
    echo 'Cli mode, please !!!';
}