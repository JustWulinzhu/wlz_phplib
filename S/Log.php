<?php
/**
 * Created by PhpStorm.
 * Author: wulinzhu
 * Email: linzhu.wu@beebank.com
 * Date: 18/8/1 下午4:01
 * 日志类
 */

namespace S;

use Config\Conf;

date_default_timezone_set('PRC');

class Log {

    private static $root_dir = '';
    private static $type;

    const LOG_TYPE_DEBUG = 'debug';
    const LOG_TYPE_WARNING = 'warning';
    const LOG_TYPE_ERROR = 'error';

    private static $log = null;
    private function __construct() {}
    private function __clone() {}

    /**
     * @param string $root_dir
     * @return Log|null
     * @throws \Exception
     */
    public static function getInstance($root_dir = '') {
        if (empty($root_dir)) {
            self::$root_dir = Conf::getConfig('log/log.root_path');
        }
        if (is_null(self::$log)) {
            self::$log = new self;
        }
        return self::$log;
    }

    /**
     * 不存在的日志类型统一走debug
     * @param $name
     * @param $arguments
     * @return bool|false|int
     * @throws \Exception
     */
    public function __call($name, $arguments) {
        $data = $arguments[0];
        $dir_name = is_array($arguments) && count($arguments) == 2 ? $arguments[1] : '';
        return $this->debug($data, $dir_name);
    }

    /**
     * debug日志
     * @param array $data
     * @param string $dir_name
     * @return bool|false|int
     * @throws \Exception
     */
    public function debug(array $data, $dir_name = '') {
        self::$type = self::LOG_TYPE_DEBUG;
        return $this->log($data, $dir_name);
    }

    /**
     * warning日志
     * @param array $data
     * @param string $dir_name
     * @return bool|false|int
     * @throws \Exception
     */
    public function warning(array $data, $dir_name = '') {
        self::$type = self::LOG_TYPE_WARNING;
        return $this->log($data, $dir_name);
    }

    /**
     * error日志 邮件发送
     * @param array $data
     * @param string $dir_name
     * @return bool|false|int
     * @throws \Exception
     */
    public function error(array $data, $dir_name = '') {
        self::$type = self::LOG_TYPE_ERROR;
        $log_ret = $this->log($data, $dir_name);

        try {
            (new Mail(Conf::getConfig('mail/exception')))->send('18515831680@163.com', '异常报警', json_encode($data));
        } catch (\Exception $e) {}
        return $log_ret;
    }

    /**
     * 日志写入
     * @param array $data
     * @param string $dir_name
     * @return bool|false|int
     * @throws \Exception
     */
    private function log(array $data, $dir_name = '') {
        if (Fun::isCli()) {
            $request_uri = $_SERVER['SCRIPT_NAME'] ? $_SERVER['SCRIPT_NAME'] : $_SERVER['SCRIPT_FILENAME'];
            $request_uri = strstr($request_uri, '.', true);
            $root_dir = substr(trim(self::$root_dir, DIRECTORY_SEPARATOR), 0, strpos(trim(self::$root_dir, DIRECTORY_SEPARATOR), DIRECTORY_SEPARATOR));
            $dir_name = $dir_name ? DIRECTORY_SEPARATOR . $dir_name : DIRECTORY_SEPARATOR . trim(trim($request_uri, DIRECTORY_SEPARATOR), $root_dir);
        } else {
            $request_uri = parse_url($_SERVER['REQUEST_URI'])['path'];
            $dir_name = $dir_name ? DIRECTORY_SEPARATOR . $dir_name : $request_uri;
        }
        //日志目录
        $dir = self::$root_dir . $dir_name . DIRECTORY_SEPARATOR . date('Ym', time());
        //日志名称
        $file = self::$type . "." . date('Ymd', time()) . ".log";
        //日志全路径
        $dir_file = $dir . DIRECTORY_SEPARATOR . $file;
        if (! file_exists($dir)) { //创建文件需要www目录的写权限,chown -R www-data:root /www,解决办法:把www目录所属者改为对应php程序执行的用户(查看php执行用户ps aux)
            mkdir($dir, 0777, true);
        }
        if (! file_exists($dir_file)) {
            touch($dir_file);
            chmod($dir_file, 0777);
        }
        $data_str = implode(" | ", $data);
        $content = "[ " . $dir_file . " ] | " . date('Y-m-d H:i:s', time()) . " | " . (isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : '') . ' | ' . (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '') . ' | ' . $data_str . "\n";
        $res = file_put_contents($dir_file, $content, FILE_APPEND);
        $res = $res ? $res : $this->write($dir_file, $content);
        if (! $res) {
            throw new \Exception('日志写入失败');
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

}