<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 20/8/20 下午3:04
 * Email: 18515831680@163.com
 *
 * ppt 下载
 *
 */

namespace App\Controller\Toppt;

use S\Log;

header('Access-Control-Allow-Origin:*');

class Download extends \App\Controller\Base {

    public $verify = false;

    /**
     * @param $args
     * @throws \Exception
     */
    public function index($args)
    {
        $file_path = urldecode(\S\Param::get('path'));
        Log::getInstance()->debug([__METHOD__, $file_path]);
        \S\Oss\Files::setHeader(pathinfo($file_path)['basename']);
        readfile($file_path);
    }

}