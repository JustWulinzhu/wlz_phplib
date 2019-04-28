<?php
/**
 * Created by PhpStorm.
 * Author: wulinzhu
 * Email: linzhu.wu@beebank.com
 * Date: 19/4/6 上午12:47
 * 文件上传下载类,调用文件系统类 $oss = new Oss();
 */

require_once dirname(__DIR__) . '/' . 'fun.php';

class Files {

    /**
     * 文件上传,返回oss文件存储路径
     * 可采用模拟文件上传方式,demo :
     * Curl::request('http://localhost/wlz_phplib/oss/files.php', 'POST', array('file' => new \CURLFile('/Users/wulinzhu/Documents/gou.png')));
     * @param $dir
     * @param $call_back_url
     * @return string
     */
    public function upload($dir, $call_back_url = '') {
        $file_path = $dir . '/' . date('Ym', time()) . '/' . $_FILES['file']['name'];
        $file_tmp_name = $_FILES['file']['tmp_name'];

        $oss = new Oss();
        $oss->uploadFile($oss::BUCKET, $file_path, $file_tmp_name, $call_back_url);
        return $file_path;
    }

    /**
     * 文件下载
     * @param $file_path
     * @param $local_file_path
     * @return bool
     */
    public function download($file_path, $local_file_path) {
        $oss = new Oss();
        $oss->get($oss::BUCKET, $file_path, $local_file_path);
        return true;
    }

}

$call_back_url = 'http://localhost/wlz_phplib/test.php';
$ret = (new Files())->upload('test');
//$ret = (new Files())->download('test/201904/gou.png', '/www_tmp/a.png');
print_r($ret);