<?php
/**
 * Created by PhpStorm.
 * Author: wulinzhu
 * Email: 18515831680@163.com
 * Date: 19/9/16 上午11:06
 */

namespace S\Oss;

use \S\Tools;
use S\Curl;
use S\Http;
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
        $files = Tools::scanDir($path);

        foreach ($files as $file) {
            if (in_array(Tools::getExtendName($file), $this->pic)) {
                $ret = \S\Http\Curl::request(
                    APP_HOST . '/test/index',
                    'POST',
                    array('file' => new \CURLFile(self::FILE_ROOT_DIR . $file))
                );
                Log::getInstance()->debug([$file, $ret]);
            }
        }
        return true;
    }

}