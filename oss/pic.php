<?php
/**
 * Created by PhpStorm.
 * Author: wulinzhu
 * Email: linzhu.wu@beebank.com
 * Date: 19/9/16 上午11:06
 */

require_once dirname(__DIR__) . '/' . 'fun.php';

class Pic {

    const FILE_ROOT_DIR = '/www/tmp/';

    private $pic = ['png', 'jpg', 'gif', 'jpeg'];

    /**
     * 批量上传图片到阿里云
     * @param $path
     * @return bool
     */
    public function upload($path) {
        $files = Fun::scanDir($path);

        foreach ($files as $file) {
            if (in_array(Fun::getExtendName($file), $this->pic)) {
                Curl::request(
                    'http://39.105.182.40/wlz_phplib/oss/files.php?upload=1',
                    'POST',
                    array('file' => new \CURLFile(self::FILE_ROOT_DIR . $file))
                );
            }
        }
        return true;
    }

}

(new Pic())->upload('/www/tmp');