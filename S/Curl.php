<?php
/**
 * curl http请求类
 * 模拟文件上传请使用 new \CURLFile('文件路径')方式, 旧版本存在'@文件路径的方式',5.5以上已废弃,
 * 可设置CURLOPT_SAFE_UPLOAD为true来禁止使用@前缀发送文件，以增加安全性。
 * 在post方法中也可以执行get请求,只需在url中拼接地址就行。
 */

namespace S;

class Curl {

    const TIME_OUT = 60;

    /**
     * request请求 POST GET
     * @param $url
     * @param string $method
     * @param array $data
     * @param int $time_out
     * @param array $header
     * @param array $cookies
     * @return bool|string
     * @throws \Exception
     */
    public static function request($url, $method = 'GET', array $data = array(), $time_out = self::TIME_OUT, $header = array(), $cookies = array()) {
        return (strtoupper($method) == 'GET') ? self::curlGet($url, $data, $time_out) : self::curlPost($url, $data, $time_out, $header, $cookies);
    }

    /**
     * url POST请求,支持带cookie请求
     * @param $url
     * @param array $data
     * @param $time_out
     * @param array $header
     * @param array $cookies
     * @return bool|string
     * @throws \Exception
     */
    private static function curlPost($url, array $data, $time_out, $header = array(), $cookies = array()) {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        if (is_array($header) && $header) {
            $curl_header = array();
            foreach ($header as $k => $v) {
                $curl_header[] = $k . ':' . $v;
            }
            curl_setopt($curl, CURLOPT_HTTPHEADER, $curl_header); //设置请求头信息
        }
        if (is_array($cookies) && $cookies) {
            $curl_cookie = '';
            foreach ($cookies as $k => $v) {
                $curl_cookie .= $k . '=' . $v . ';';
            }
            $curl_cookie = rtrim($curl_cookie, ';');
            curl_setopt($curl, CURLOPT_COOKIE, $curl_cookie); //设置cookie
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); //设置稍curl_exec函数请求URL的返回结果，而不是把返回结果定向到标准输出并返回true
        curl_setopt($curl, CURLOPT_HEADER, false); //是否返回HTTP头信息
        curl_setopt($curl, CURLOPT_POST, true); //设置post方式请求
        curl_setopt($curl, CURLOPT_TIMEOUT, $time_out); //设置超时时间
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data); //设置post请求参数
        $response = curl_exec($curl);
        if (0 != curl_errno($curl)) {
            Log::getInstance()->warning(array('CURL POST error msg', curl_error($curl)));
            if (curl_errno($curl) == CURLE_OPERATION_TIMEDOUT) {
                throw new \S\Exceptions(curl_error($curl));
            }
        }
        Log::getInstance()->debug(array('CURL POST INFO', $url, $response, json_encode(curl_getinfo($curl))));
        curl_close($curl);

        return $response;
    }

    /**
     * curl GET请求
     * @param $url
     * @param $data
     * @param $time_out
     * @return bool|string
     * @throws \Exception
     */
    private static function curlGet($url, $data, $time_out) {
        $curl = curl_init();

        $url = empty($data) ? $url : $url . '?' . http_build_query($data);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_TIMEOUT, $time_out);
        $response = curl_exec($curl);
        if (0 != curl_errno($curl)) {
            Log::getInstance()->warning(array('CURL GET error msg', curl_error($curl)));
            if (curl_errno($curl) == CURLE_OPERATION_TIMEDOUT) {
                throw new \S\Exceptions(curl_error($curl));
            }
        }
        Log::getInstance()->debug(array('CURL GET INFO', $url, $response, json_encode(curl_getinfo($curl))));
        curl_close($curl);

        return $response;
    }

}