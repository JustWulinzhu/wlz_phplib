<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 20/4/13 上午1:16
 * Email: 18515831680@163.com
 *
 * 参数类
 *
 */

namespace S;

class Param {

    /**
     * @param $field
     * @param string $default
     * @return mixed|string
     */
    public static function request($field = '', $default = '') {
        if (empty($field)) {
            return $_REQUEST;
        }
        return isset($_REQUEST[$field]) ? $_REQUEST[$field] : $default;
    }

    /**
     * @param $field
     * @param string $default
     * @return mixed|string
     */
    public static function post($field = '', $default = '') {
        if (empty($field)) {
            return $_POST;
        }
        return isset($_POST[$field]) ? $_POST[$field] : $default;
    }

    /**
     * 获取get参数
     * @param string $field
     * @param string $default
     * @return array|mixed|string
     */
    public static function get($field = '', $default = '') {
        $uri_arr = parse_url($_SERVER['REQUEST_URI']);
        $params = array_filter(explode("&", isset($uri_arr['query']) ? $uri_arr['query'] : ''));
        $request_params = [];
        foreach ($params as $param) {
            $value = explode("=", $param);
            $request_params[current($value)] = end($value);
        }

        if ($field) {
            return isset($request_params[$field]) ? $request_params[$field] : $default;
        }

        return $request_params;
    }

}