<?php
/**
 * Created by PhpStorm.
 * Author: wulinzhu
 * Email: linzhu.wu@beebank.com
 * Date: 19/9/2 上午12:10
 * 获取配置文件类
 * 命名配置文件方式: 在config目录下面创建php文件, 直接返回数组, key=>value方式定义配置内容, 支持多维形式
 * 例: Conf::getConfig('mail/user.name'), 获取mail.php里面的user字段下面的name字段
 */

require_once(dirname(__DIR__) . "/fun.php");

class Conf {

    private static $conf = array();

    /**
     * 引入配置文件
     * @param $file
     * @throws Exception
     */
    public static function getFileData($file) {
        $conf_file = explode("/", $file);
        list($file) = $conf_file;
        $path = __DIR__ . '/' . $file . '.php';
        if (! file_exists($path)) {
            throw new \Exception('config file not exist');
        }
        $data = require $path;
        if (! is_array($data)) {
            throw new \Exception('config file returns must be array');
        }

        self::$conf = $data;
    }

    /**
     * 获取配置
     * @param $key
     * @return array|mixed
     * @throws Exception
     */
    public static function getConfig($key = '') {
        self::getFileData($key);
        $config = self::$conf;
        $nodes = array_filter(explode("/", $key));
        if (count($nodes) == 1) return $config;
        list($file, $node) = $nodes;
        $keys = explode('.', $node);

        foreach ($keys as $num => $item) {
            $keys[$item] = $item;
            unset($keys[$num]);
        }
        foreach ($keys as $item) {
            if (! isset($config[$item])) {
                continue;
            }
            $config = $config[$item];
            if ($item == end($keys)) {
                return $config;
            }
        }
        throw new Exception("can not find config");
    }

}