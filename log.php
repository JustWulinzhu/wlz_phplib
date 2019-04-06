<?php
/**
 * Created by PhpStorm.
 * Author: wulinzhu
 * Email: linzhu.wu@beebank.com
 * Date: 18/8/1 下午4:01
 * 日志类
 */
date_default_timezone_set('PRC');
class Log {

    private static $root_dir = '/www/log';
    private static $type;

    const LOG_TYPE_DEBUG = 'debug';
    const LOG_TYPE_WARNING = 'warning';
    const LOG_TYPE_ERROR = 'error';

    private static $log = null;
    private function __construct() {}
    private function __clone() {}

    public static function getInstance() {
        if (is_null(self::$log)) {
            self::$log = new self;
        }
        return self::$log;
    }

    /**
     * debug日志
     * @param array $data
     * @param string $dir_name
     * @return bool|int
     */
    public function debug(array $data, $dir_name = '') {
        self::$type = self::LOG_TYPE_DEBUG;
        return $this->log($data, $dir_name);
    }

    /**
     * warning日志
     * @param array $data
     * @param string $dir_name
     * @return bool|int
     */
    public function warning(array $data, $dir_name = '') {
        self::$type = self::LOG_TYPE_WARNING;
        return $this->log($data, $dir_name);
    }

    /**
     * error日志
     * @param array $data
     * @param string $dir_name
     * @return bool|int
     */
    public function error(array $data, $dir_name = '') {
        self::$type = self::LOG_TYPE_ERROR;
        return $this->log($data, $dir_name);
    }

    /**
     * @param array $data
     * @param string $dir_name
     * @return bool|int
     * @throws Exception
     */
    private function log(array $data, $dir_name = '') {
        if (self::isCli()) {
            $request_uri = $_SERVER['SCRIPT_NAME'] ? $_SERVER['SCRIPT_NAME'] : $_SERVER['SCRIPT_FILENAME'];
            $request_uri = strstr($request_uri, '.', true);
            $root_dir = substr(trim(self::$root_dir, '/'), 0, strpos(trim(self::$root_dir, '/'), '/'));
            $dir_name = $dir_name ? "/" . $dir_name : trim(trim($request_uri, '/'), $root_dir);
        } else {
            $request_uri = $_SERVER['REQUEST_URI'] ? $_SERVER['REQUEST_URI'] : $_SERVER['DOCUMENT_URI'];
            $dir_name = $dir_name ? "/" . $dir_name : strstr($request_uri, '.', true);
        }
        $dir = self::$root_dir . $dir_name . "/" . date('Ym', time());
        $file = self::$type . "." . date('Ymd', time()) . ".log";
        $dir_file = $dir . '/' . $file;
        if (!file_exists($dir)) { //创建文件需要www目录的写权限,chown -R www-data:root /www,解决办法:把www目录所属者改为对应php程序执行的用户(查看php执行用户ps aux)
            mkdir($dir, 0755, true);
        }
        if (!file_exists($dir_file)) {
            touch($dir_file);
        }
        $data_str = implode(" | ", $data);
        $content = "[ " . $dir_file . " ] | " . date('Y-m-d H:i:s', time()) . " | " . (isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : '') . ' | ' . (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '') . ' | ' . $data_str . "\n";
        $res = file_put_contents($dir_file, $content, FILE_APPEND);
        $res =  $res ? $res : $this->write($dir_file, $content);
        if (!$res) {
            throw new Exception('日志写入失败');
        }
        return $res;
    }

    /**
     * 写入文件fopen方式
     * @param $file
     * @param $content
     * @return bool
     */
    private function write($file, $content) {
        $file = fopen($file, 'a+');
        $res = fwrite($file, $content);
        fclose($file);
        return $res ? true : false;
    }

    /**
     * 判断是否为cli模式
     * @return bool
     */
    public static function isCli(){
        return preg_match("/cli/i", php_sapi_name()) ? true : false;
    }

}