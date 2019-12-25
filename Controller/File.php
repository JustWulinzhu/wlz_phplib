<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 19/12/25 下午5:12
 * Email: 18515831680@163.com
 */

namespace Controller;

class File
{

    /**
     * 文件上传
     * @return string
     * @throws \S\Exceptions
     */
    public function upload() {
        $path = '';
        $dir = '';

        $oss = new \S\Oss\Files();
        $ret = $oss->uploadLocal($path, $dir);

        return $ret;
    }

    /**
     * 文件下载
     * @throws \Exception
     */
    public function download() {
        $path = 'test/201912/b.txt';

        $oss = new \S\Oss\Files();
        $oss->output($path);
    }

}