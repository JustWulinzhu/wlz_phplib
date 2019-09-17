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
     * @param null $local_file_path
     * @return string 文件流
     */
    public function download($file_path, $local_file_path = null) {
        $oss = new Oss();
        $file = $oss->get($oss::BUCKET, $file_path, $local_file_path);
        return $file;
    }

}

if (isset($_GET['upload']) && $_GET['upload'] == 1) {
    (new Files())->upload('iphone_picture');
} else if (isset($_GET['download']) && $_GET['download'] == 1) {
    header("Content-Type: application/octet-stream");
    header("Content-Disposition: attachment; filename=85_3141b373.png");
    header("Pragma: no-cache");
    header("Expires: 0");
    $ret = (new Files())->download('test/201904/85_3141b373.png');
    echo $ret;
} else {
    echo 'do nothing';
}