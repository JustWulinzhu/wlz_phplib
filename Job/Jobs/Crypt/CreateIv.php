<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 19/11/9 下午3:37
 * Email: 18515831680@163.com
 * 生成aes加密所需iv参数，固定长度16字节
 */

namespace Job\Jobs\Crypt;

class CreateIv implements \Job\Base {

    /**
     * @param null $argv
     * @throws \Exception
     */
    public function exec($argv = null) {
        if (\S\Fun::isCli()) {
            $iv = substr(md5(microtime(true)), 0, 16);
            \S\Log::getInstance()->debug(array('create_iv', $iv));
            echo $iv . "\n";
        } else {
            echo 'Cli mode, please !!!';
        }
    }

}