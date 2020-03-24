<?php
//工具类

namespace S;

header("content-type='text/html',charset='utf-8'");

class Fun
{

    /**
     * http状态码
     * @param $code
     * @return mixed
     */
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

    /**
     * 二维数组按照某个字段排序
     * @param $arr
     * @param $field
     * @param string $sort
     * @return array
     */
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

    /**
     * $arr = ['中国', '美国', '日本', '韩国', '德国', '中国', '美国', '中国', '日本'];
     * 计算每个值出现的次数
     * @param $arr
     * @return array
     */
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

    /**
     * 判断当前请求是否是XMLHttpRequest(AJAX)
     * @return boolean
     */
    public static function isAjax() {
        return ($_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') ? true : false;
    }

    /**
     * 判断数组中是否有重复值
     * @param $arr
     * @return bool
     */
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

    /**
     * byte字节转M
     * @param $size
     * @return string
     */
    public static function formatSize($size) {
        $kb = $size / 1024;
        $mb = $kb / 1024;
        if ($mb < 1) {
            return number_format($kb, 3, '.', '') . ' KB';
        }
        return number_format($mb, 3, '.', '') . ' M';
    }

    /**
     * 获取http请求头信息
     * @return array
     */
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
     * 获取手机客户端操作系统类型
     * @return bool|string
     */
    public static function getClientType() {
        $http_user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);
        if (
            strpos($http_user_agent, 'iPhone')
            || strpos($http_user_agent, 'iPad')
            || strpos($http_user_agent, 'iOS')
        )
        {
            return 'iOS';
        } else if (strpos($http_user_agent, 'android')) {
            return 'android';
        } else {
            return false;
        }
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
        if (! $res) {
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
    public static function reverse2($str) {
        if (empty($str)) {
            return false;
        }
        $arr = str_split($str);
        krsort($arr);
        return implode("", $arr);
    }

    /**
     * 逐行读取文件
     * @param $file
     * @return array
     * @throws \Exception
     */
    public static function readFile($file) {
        if (! file_exists($file)) {
            throw new \Exception('文件不存在');
        }
        $file = fopen($file, 'r');

        $file_arr = array();
        while (! feof($file)) { //feof判断是否到达文件末尾
            $line = fgets($file); // 逐行读取文件
            $file_arr[] = trim($line);
        }
        fclose($file);

        $file_arr = array_filter($file_arr);
        $file_arr = array_values($file_arr);

        return $file_arr;
    }

    /**
     * 递归遍历文件夹
     * @param $dir_name
     * @return array
     * @throws \Exception
     */
    public static function scanDir($dir_name) {
        if (! is_readable($dir_name)) {
            throw new \Exception('目录不可读');
        }
        $resource = opendir($dir_name);
        $files = array();
        while (false !== ($file = readdir($resource))) {
            if ($file == '.' || $file == '..') continue;
            if (is_dir($dir_name . '/' . $file)) {
                $files[$file] = self::scanDir($dir_name . '/' . $file);
            } else {
                $files[] = $file;
            }
        }
        closedir($resource);
        return $files;
    }

    /**
     * 递归处理数组
     * @param $arr
     * @return array|bool
     */
    public static function recData($arr) {
        if (! is_array($arr)) return false;

        $data = [];
        foreach ($arr as $item) {
            if (is_array($item)) {
                $data = array_merge($data, self::recData($item));
            } else {
                $data[] = $item;
            }
        }
        return $data;
    }

    /**
     * 获取文件扩展名
     * @param $file_name
     * @return string
     */
    public static function getExtendName($file_name) {
        return strtolower(substr(strrchr($file_name, "."), 1));
    }

    /**
     * xml转obj
     * @param $xmlObject
     * @return array|string
     */
    public static function xmlToArray($xmlObject){
        $result = array();
        foreach ((array) $xmlObject as $index => $node) {
            $result[$index] = (is_object($node) || is_array($node)) ? self::xmlToArray($node) : $node;
        }

        return (is_array($result) && empty($result)) ? "" : $result;
    }

    /**
     * 判断是否为多维数组
     * @param $array
     * @return bool false不是,true是
     */
    public static function isArrayMultiDimension($array) {
        return (count($array) == count($array, COUNT_RECURSIVE)) ? false : true;
    }

    /**
     * 递归对象转数组
     * @param $obj
     * @return array
     */
    public static function objToArray($obj) {
        if (is_object($obj) && (! is_array($obj))) {
            $obj = (array)$obj;
        }
        if (is_array($obj)) {
            foreach ($obj as &$obj_item) {
                if (is_object($obj_item) || is_array($obj_item)) {
                    $obj_item = self::objToArray($obj_item);
                }
            }
        }

        return $obj;
    }

    /**
     * 元转万元,避免精度问题(优先使用bc函数)
     * @param $num
     * @return string
     */
    public static function bcDivNumber($num) {
        $num = intval($num) / 10000;;
        $arr = explode('.', $num);
        $int = $arr[0];
        $float = count($arr) == 2 ? substr($arr[1], 0, 2) : '00';
        return $int . '.' . $float;
    }

    /**
     * 生成脱敏银行卡
     * @param $bank_card
     * @return string
     */
    public static function getEncryptBankCard($bank_card) {
        return str_repeat('*', strlen($bank_card) - 4) . substr($bank_card, -4);
    }

    /**
     * 十进制转62进制。
     * 可优化功能, 动态设置转换后的进制
     * @param $num
     * @return string
     */
    public static function numTransform($num) {
        $num = intval($num);
        $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $len = strlen($str);
        $charArr = str_split($str);

        $char = '';
        do {
            $key = ($num - 1) % $len;
            $char = $charArr[$key] . $char;
            $num = floor(($num - $key) / $len);
        } while ($num > 0);
        return $char;
    }

    /**
     * 剩余秒数
     * @param string $type
     * @return false|int
     * @throws \Exception
     */
    public static function getLeftSeconds($type = 'day') {
        switch ($type) {
            case 'day' :
                $seconds = strtotime(date('Ymd', strtotime('+1 day'))) - time();
                break;
            case 'week' :
                $seconds = strtotime(date('Ymd', strtotime('+1 week')) - time());
                break;
            case 'month' :
                $seconds = strtotime(date('Ymd', strtotime('+1 month')) - time());
                break;
            case 'year' :
                $seconds = strtotime(date('Ymd', strtotime('+1 year')) - time());
                break;
            default :
                throw new \Exception('错误的时间单位');
        }
        return $seconds;
    }

    /**
     * 0-100内的数字中文转换
     * @param $num
     * @return mixed|string
     * @throws Exceptions
     */
    public static function numTrans($num) {
        if (! is_numeric($num)) {
            throw new \S\Exceptions("数字格式错误");
        }
        if ($num > 99) {
            throw new \S\Exceptions("数字超出范围，只能输入100以下的数字");
        }

        $chinese_num_data = ['一', '二', '三', '四', '五', '六', '七', '八', '九', '十'];

        if ($num % 10 == 0) {
            $chinese_num = $chinese_num_data[substr($num, 0, 1) - 1] . end($chinese_num_data);
        } else {
            if ($num <= 10) {
                $chinese_num = $chinese_num_data[$num - 1];
            } else if ($num > 10 && $num < 20) {
                $chinese_num = end($chinese_num_data) . $chinese_num_data[substr($num, -1) - 1];
            } else {
                $chinese_num = $chinese_num_data[substr($num, 0, 1) - 1] . end($chinese_num_data) . $chinese_num_data[substr($num, -1) - 1];
            }
        }

        return $chinese_num;
    }
    
}