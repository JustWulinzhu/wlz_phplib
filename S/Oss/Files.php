<?php
/**
 * Created by PhpStorm.
 * Author: wulinzhu
 * Email: linzhu.wu@beebank.com
 * Date: 19/4/6 上午12:47
 * 文件上传下载类,调用文件系统类 $oss = new Oss();
 */

namespace S\Oss;

use S\Exceptions;
use S\Oss\Oss;

class Files {

    /**
     * * 文件上传,返回oss文件存储路径
     * 可采用模拟文件上传方式,demo :
     * Http::request('http://localhost/wlz_phplib/oss/files.php', 'POST', array('file' => new \CURLFile('/Users/wulinzhu/Documents/gou.png')));
     * @param $dir
     * @param string $call_back_url
     * @return string
     * @throws \Exception
     */
    public function upload($dir, $call_back_url = '') {
        $file_path = $dir . DIRECTORY_SEPARATOR . date('Ym', time()) . DIRECTORY_SEPARATOR . $_FILES['file']['name'];
        $file_tmp_name = $_FILES['file']['tmp_name'];

        $oss = new Oss();
        $oss->uploadFile(\S\Oss\Oss::BUCKET, $file_path, $file_tmp_name, $call_back_url);
        return $file_path;
    }

    /**
     * 本地上传
     * @param $upload_file_path
     * @param $dir
     * @param string $call_back_url
     * @return string
     * @throws Exceptions
     * @throws \Exception
     */
    public function uploadLocal($upload_file_path, $dir, $call_back_url = '') {
        if (! file_exists($dir)) {
            throw new \S\Exceptions('文件不存在。');
        }
        $file_path = $dir . DIRECTORY_SEPARATOR . date('Ym', time()) . DIRECTORY_SEPARATOR . pathinfo($upload_file_path)['basename'];

        $oss = new Oss();
        $oss->uploadFile(\S\Oss\Oss::BUCKET, $file_path, $upload_file_path, $call_back_url);
        return $file_path;
    }

    /**
     * 文件下载
     * @param $file_path
     * @param null $local_file_path
     * @return string
     * @throws \Exception
     */
    public function download($file_path, $local_file_path = null) {
        $oss = new Oss();
        $file = $oss->get(\S\Oss\Oss::BUCKET, $file_path, $local_file_path);
        return $file;
    }

    /**
     * 浏览器输出下载
     * @param $file_path
     * @throws \Exception
     */
    public function output($file_path) {
        $file_name = pathinfo($file_path)['basename'];
        $ret = $this->download($file_path);

        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename={$file_name}");
        header("Pragma: no-cache");
        header("Expires: 0");

        exit($ret);
    }

}