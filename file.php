<?php
/**
 * Created by PhpStorm.
 * Author: wulinzhu
 * Email: linzhu.wu@beebank.com
 * Date: 18/12/26 上午11:27
 */
require_once "fun.php";

class File {

    /**
     * 逐行读取文件
     * fgetss 是读取一行文件但是会去掉html标记
     * @param $file_name
     * @return array
     * @throws ExceptionService
     */
    public static function getFileContents($file_name) {
        if (! file_exists($file_name)) {
            throw new ExceptionService('文件不存在');
        }
        $file = fopen($file_name, 'r');
        $file_arr = array();
        while (! feof($file)) { //feof判断是否到达文件末尾
            $line = trim(fgets($file)); // 逐行读取文件
            $file_arr[] = $line;
        }
        fclose($file);
        return $file_arr;
    }

    /**
     * 生成文件
     */
    public static function readFile() {
        $name = "/Users/wulinzhu/Desktop/name_new.txt";
        $mobile = "/Users/wulinzhu/Desktop/mobile_new.txt";
        $name = self::getFileContents($name);
        $mobile = self::getFileContents($mobile);
        $data = array();
        foreach ($name as $k => $v) {
            $data[] = array($v, $mobile[$k]);
            file_put_contents('/Users/wulinzhu/Desktop/beebank_new.txt', $v . ' ' . $mobile[$k] . "\n", FILE_APPEND);
        }
        Log::getInstance()->debug(array('file contents', json_encode($data)));
    }

}