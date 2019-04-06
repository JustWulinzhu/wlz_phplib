<?php
//公共函数库

require_once "print.php";
require_once "log.php";
require_once "ipc.php";
require_once "mysql.php";
require_once "curl.php";
require_once "queue/queue.php";
require_once "exceptionService.php";
require_once "oss/oss.php";
require_once "base.php";

class Fun
{

    //http状态码
    public static function http($code) {
        $http = array(
            100 => "HTTP/1.1 100 Continue",
            101 => "HTTP/1.1 101 Switching Protocols",
            200 => "HTTP/1.1 200 OK",
            201 => "HTTP/1.1 201 Created",
            202 => "HTTP/1.1 202 Accepted",
            203 => "HTTP/1.1 203 Non-Authoritative Information",
            204 => "HTTP/1.1 204 No Content",
            205 => "HTTP/1.1 205 Reset Content",
            206 => "HTTP/1.1 206 Partial Content",
            300 => "HTTP/1.1 300 Multiple Choices",
            301 => "HTTP/1.1 301 Moved Permanently",
            302 => "HTTP/1.1 302 Found",
            303 => "HTTP/1.1 303 See Other",
            304 => "HTTP/1.1 304 Not Modified",
            305 => "HTTP/1.1 305 Use Proxy",
            307 => "HTTP/1.1 307 Temporary Redirect",
            400 => "HTTP/1.1 400 Bad Request",
            401 => "HTTP/1.1 401 Unauthorized",
            402 => "HTTP/1.1 402 Payment Required",
            403 => "HTTP/1.1 403 Forbidden",
            404 => "HTTP/1.1 404 Not Found",
            405 => "HTTP/1.1 405 Method Not Allowed",
            406 => "HTTP/1.1 406 Not Acceptable",
            407 => "HTTP/1.1 407 Proxy Authentication Required",
            408 => "HTTP/1.1 408 Request Time-out",
            409 => "HTTP/1.1 409 Conflict",
            410 => "HTTP/1.1 410 Gone",
            411 => "HTTP/1.1 411 Length Required",
            412 => "HTTP/1.1 412 Precondition Failed",
            413 => "HTTP/1.1 413 Request Entity Too Large",
            414 => "HTTP/1.1 414 Request-URI Too Large",
            415 => "HTTP/1.1 415 Unsupported Media Type",
            416 => "HTTP/1.1 416 Requested range not satisfiable",
            417 => "HTTP/1.1 417 Expectation Failed",
            500 => "HTTP/1.1 500 Internal Server Error",
            501 => "HTTP/1.1 501 Not Implemented",
            502 => "HTTP/1.1 502 Bad Gateway",
            503 => "HTTP/1.1 503 Service Unavailable",
            504 => "HTTP/1.1 504 Gateway Time-out"
        );
        return $http[$code];
    }

    //多维数组按照某个字段排序
    public static function arraySort($arr, $field, $sort = 'ASC') {
        $data = [];
        foreach ($arr as $k => $v) {
            $data[] = $v[$field];
        }
        if ($sort === 'ASC') {
            asort($data);
        } else {
            arsort($data);
        }
        $result_arr = [];
        foreach ($data as $k => $v) {
            $result_arr[$k] = $arr[$k];
        }
        return $result_arr;
    }

    //$arr = ['中国', '美国', '日本', '韩国', '德国', '中国', '美国', '中国', '日本'];
    //计算每个值出现的次数
    public static function arrayKeysCount($arr) {
        $array_unique = array_unique($arr);
        $data = [];
        foreach ($array_unique as $value) {
            $i = 0;
            foreach ($arr as $k => $v) {
                if ($v == $value) {
                    $i++;
                    $data[$value] = '出现' . $i . '次';
                }
            }
        }
        return $data;
    }

    //判断数组中是否有重复值
    public static function isArrayRepeat($arr) {
        for ($i = 0; $i < count($arr); $i++) {
            for ($j = $i + 1; $j < count($arr); $j++) {
                if ($arr[$i] == $arr[$j]) {
                    return true;
                }
            }
        }
        return false;
    }

    //折半查找
    public static function binarySearch($arr, $value) {
        $low = 0;
        $high = count($arr);
        while ($low <= $high) {
            $mid = intval(($low + $high) / 2);
            if ($value == $arr[$mid]) return $mid;
            if ($arr[$mid] > $value) $high = $mid - 1;
            if ($arr[$mid] < $value) $low = $mid + 1;
        }
    }

    //冒泡排序
    public static function maoPao($arr) {
        if (!is_array($arr)) return false;
        $count = count($arr);
        for ($i = 0; $i < $count; $i++) {
            for ($j = 1; $j < $count - $i; $j++) {

            }
        }
        return $arr;
    }

    //处理坐标数据
    public static function pointFormat($str) {
        $data = [];
        $arr = explode("\n", $str); //以换行符分割成数组
        foreach ($arr as $v) {
            $s = explode(" ", $v);
            $data[] = current($s);
            $data[] = end($s);
        }
        $data = implode(',' . "\n", $data); //以, 和 换行符 分割数组为字符串
        return $data;
    }

    //byte字节转M
    public static function formatSize($size) {
        $kb = $size / 1024;
        $mb = $kb / 1024;
        if ($mb < 1) {
            return number_format($kb, 3, '.', '') . ' KB';
        }
        return number_format($mb, 3, '.', '') . ' M';
    }

    //二维数组按照某个值排序
    public static function arraySortByField($array, $key, $order = "asc") {
        $arr_num = $arr = array();
        foreach ($array as $k => $v) {
            $arr_num[$k] = $v[$key];
        }
        if ('asc' == $order) {
            asort($arr_num);
        } else {
            arsort($arr_num);
        }
        foreach ($arr_num as $k => $v) {
            $arr[$k] = $array[$k];
        }
        return $arr;
    }

    //获取http请求头信息
    public static function getHttpHeaders() {
        $headers = array();
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }

    /**
     * 判断是否为cli模式
     * @return bool
     */
    public static function isCli() {
        return preg_match("/cli/i", php_sapi_name()) ? true : false;
    }

    /**
     * 判断客户端请求的来源,同样的可以判断ios和安卓
     * @return bool|string
     */
    public static function getBrowserType() {
        if (empty($_SERVER['HTTP_USER_AGENT'])) {
            return 'robot！';
        }
        if ((strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE')) && (strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== false)) {
            return 'Internet Explorer 11.0';
        }
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 10.0')) {
            return 'Internet Explorer 10.0';
        }
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9.0')) {
            return 'Internet Explorer 9.0';
        }
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.0')) {
            return 'Internet Explorer 8.0';
        }
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 7.0')) {
            return 'Internet Explorer 7.0';
        }
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 6.0')) {
            return 'Internet Explorer 6.0';
        }
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'Edge')) {
            return 'Edge';
        }
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox')) {
            return 'Firefox';
        }
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome')) {
            return 'Chrome';
        }
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'Safari')) {
            return 'Safari';
        }
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'Opera')) {
            return 'Opera';
        }
        if (strpos($_SERVER['HTTP_USER_AGENT'], '360SE')) {
            return '360SE';
        }
        //微信浏览器
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessage')) {
            return 'MicroMessage';
        }
        return false;
    }

    /**
     * 获取字符串编码方式
     * @param $str
     * @return string
     */
    public static function getUnicodeByStr($str) {
        return mb_detect_encoding($str, array('ASCII', 'UTF-8', 'GBK', 'GB2312', 'BIG'));
    }

    /**
     * 写入文件追加方式
     * @param $file
     * @param $content
     * @return bool
     */
    public static function write($file, $content) {
        $res = file_put_contents($file, $content, FILE_APPEND);
        if (!$res) {
            $res = self::fWrite($file, $content);
        }
        return $res ? true : false;
    }

    /**
     * 写入文件fopen方式
     * @param $file
     * @param $content
     * @return bool
     */
    public static function fWrite($file, $content) {
        $file = fopen($file, 'a+');
        $res = fwrite($file, $content);
        fclose($file);
        return $res ? true : false;
    }

    /**
     * 获取文件内容(一定规则)
     * @param $file
     * @return array|bool
     */
    public static function getContentsByFile($file) {
        if (file_exists($file)) {
            $str = file_get_contents($file);
            $str = str_replace("\n", ' ', $str);
            $str = trim($str);
            return explode(' ', $str);
        }
        return false;
    }

    /**
     * 反转字符串
     * @param $str
     * @return bool|string
     */
    public static function reverse($str) {
        if (empty($tr)) {
            return false;
        }
        $length = strlen($str);
        $new_str = '';
        for ($i = $length - 1; $i >= 0; $i--) {
            $new_str .= $str{$i};
        }
        return $new_str;
    }

    /**
     * 反转字符串2
     * @param $str
     * @return bool|string
     */
    public function reverse2($str) {
        if (empty($str)) {
            return false;
        }
        $arr = str_split($str);
        krsort($arr);
        return implode("", $arr);
    }

    /**
     * 二分查找(折半查找)
     * @param $arr
     */
    public static function zheBan($arr) {

    }

    /**
     * 逐行读取文件
     * fgetss 是读取一行文件但是会去掉html标记
     * @param $file
     * @return array
     * @throws ExceptionService
     */
    public static function getFileContents($file) {
        if (! file_exists($file)) {
            throw new ExceptionService('文件不存在');
        }
        $file = fopen($file, 'r');
        $file_arr = array();
        while (! feof($file)) { //feof判断是否到达文件末尾
            $line = fgets($file); // 逐行读取文件
            $file_arr[] = $line;
        }
        fclose($file);
        return $file_arr;
    }

    /**
     * 递归遍历文件夹
     * @param $dir_name
     * @return array
     */
    public static function scanDir($dir_name) {
        $dir = opendir($dir_name);
        $files = array();
        while ($file = readdir($dir)) {
            if ($file == '.' || $file == '..') continue;
            if (is_dir($dir_name . '/' . $file)) {
                echo '目录: ' . $file . "<br/>";
                $files[$file] = self::scanDir($dir_name . '/' . $file);
            } else {
                echo '文件:' . $file . "<br/>";
                $files[] = $file;
            }
        }
        closedir($dir);
        return $files;
    }

    /**
     * 获取文件扩展名
     * @param $file_name
     * @return string
     */
    public static function getExtendName($file_name) {
        return strtolower(substr(strrchr($file_name, "."), 1));
    }

}