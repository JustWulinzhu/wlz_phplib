<?php
/**
 * Created by PhpStorm.
 * Author: wulinzhu
 * Email: 18515831680@163.com
 * Date: 19/4/6 上午12:47
 * 文件上传下载类,调用文件系统类 $oss = new Oss();
 */

namespace S\Oss;

use S\Exceptions;
use S\Log;
use S\Oss\Oss;

class Files {

    const DEFAULT_PART_SIZE = 5242880;
    const QUEUE_LARGE_FILE_UPLOAD_KEY = 'LARGE_FILE_UPLOAD';

    /**
     * 获取存储路径
     * @param $dir
     * @param $local_file_path
     * @return string
     */
    private static function getDefaultFileStoragePath($dir, $local_file_path) {
        return $dir . DIRECTORY_SEPARATOR . date('Ym', time()) . DIRECTORY_SEPARATOR . pathinfo($local_file_path)['basename'];
    }

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
        $file_path = self::getDefaultFileStoragePath($dir, $_FILES['file']['name']);
        $file_tmp_name = $_FILES['file']['tmp_name'];

        $oss = new Oss();
        $oss->uploadFile(\S\Oss\Oss::BUCKET, $file_path, $file_tmp_name, $call_back_url);
        return $file_path;
    }

    /**
     * 本地上传
     * @param $local_file_path
     * @param $dir
     * @param string $call_back_url
     * @return string
     * @throws Exceptions
     * @throws \Exception
     */
    public function uploadLocal($local_file_path, $dir, $call_back_url = '') {
        if (! file_exists($dir)) {
            throw new \S\Exceptions('文件不存在。');
        }
        $file_path = self::getDefaultFileStoragePath($dir, $local_file_path);

        $oss = new Oss();
        $oss->uploadFile(\S\Oss\Oss::BUCKET, $file_path, $local_file_path, $call_back_url);
        return $file_path;
    }

    /**
     * 分片上传
     * @param $local_file_path
     * @param $dir
     * @param int $part_size
     * @return bool|string
     * @throws Exceptions
     * @throws \Exception
     */
    public function partUpload($local_file_path, $dir, $part_size = self::DEFAULT_PART_SIZE) {
        if (! file_exists($local_file_path)) {
            throw new \S\Exceptions('文件不存在！');
        }

        $file_path = self::getDefaultFileStoragePath($dir, $local_file_path);

        $data = [
            'bucket' => \S\Oss\Oss::BUCKET,
            'local_file_path' => $local_file_path,
            'file_path' => $file_path,
            'part_size' => $part_size,
        ];
        $queue = new \S\Queue\Redis\Redis();
        Log::getInstance()->debug(['large file upload push data', json_encode($data)]);
        $ret = $queue->push(self::QUEUE_LARGE_FILE_UPLOAD_KEY, json_encode($data));
        return $ret ? $file_path : false;
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
        self::setHeader($file_name);

        exit($ret);
    }

    /**
     * 设置浏览器输出头信息
     * @param $file_name
     * @return bool
     */
    public static function setHeader($file_name) {
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename={$file_name}");
        header("Pragma: no-cache");
        header("Expires: 0");

        return true;
    }

}