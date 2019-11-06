<?php
/**
 * Created by PhpStorm.
 * Author: wulinzhu
 * Email: linzhu.wu@beebank.com
 * Date: 19/9/16 上午11:06
 */

namespace S\Oss;

use S\Fun;
use S\Curl;
use S\Log;

class Pic {

    const FILE_ROOT_DIR = '/iphone/20190917-031758/';

    private $pic = ['png', 'jpg', 'gif', 'jpeg'];

    /**
     * 批量上传图片到阿里云
     * @param $path
     * @return bool
     * @throws \Exception
     */
    public function upload($path) {
        $files = Fun::scanDir($path);

        foreach ($files as $file) {
            if (in_array(Fun::getExtendName($file), $this->pic)) {
                $ret = Curl::request(
                    'http://39.105.182.40/wlz_phplib/oss/files.php?upload=1',
                    'POST',
                    array('file' => new \CURLFile(self::FILE_ROOT_DIR . $file))
                );
                Log::getInstance()->debug([$file, $ret]);
            }
        }
        return true;
    }

}

(new Pic())->upload('/iphone/20190917-031758');