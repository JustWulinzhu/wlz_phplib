<?php
/**
 * Created by PhpStorm.
 * Author: wulinzhu
 * Email: linzhu.wu@beebank.com
 * Date: 18/8/23 上午10:18
 * 接口权限验证类,这个只提供了参数验证的方法,正式环境还需要对请求时间进行验证,超过一定秒数返回请求超时
 * 一般属于接口请求第一步,可设置成基类,每个控制器需要去继承他,执行权限验证
 * 在进行权限验证前需要对请求携带的token进行验证,方式是匹配uid和token是否是同一个用户
 * uid和token的关系应该在服务端下发token给客户端的时候进行记录,例如记录在redis里面
 * token和uid维护:在用户登录时记录两者之间的关系,在退出时清除
 */

namespace S;

use S\Fun;

class Sign {

    const TIMESTAMP_FILE = '/www/sign_timestamp.txt';

    /**
     * 接口权限验证
     * @param $time
     * @param array $param
     * @return string
     */
    public static function auth($time, array $param) {
        $param_str = '';
        if (!empty($param)) {
            ksort($param);
            $param = array_values($param);
            $param_str = strtoupper(implode("", $param));
        }
        return substr(md5($time . '|' . $param_str), 0, 18);
    }

    /**
     * 带token权限验证,一般是验证需要登录的接口,用户登录之后
     * 服务端生成token令牌下发给客户端,客户端请求接口的时候需要带上token、时间戳、请求参数等进行验证
     * 如果参数以及token泄露会导致接口过期前被恶意调用,目前想到几个办法:把每次请求的时间戳记录下来,比如记到文件里面,
     * 每次请求对参数的时间和文件里面的进行比较,如果文件里面有存在这个时间戳,就认为是人为调用接口,返回错误。
     * 存放时间戳的文件也要定时清理
     * 缺点:如果同一秒内请求量比较大的话也能会出现某些请求直接返回false,解决办法:可以以用户或者请求方(根据业务决定)为维度建立不同的时间戳存放文件
     * 或者用redis计数相同参数的请求数不能达到一定值
     * @param $time
     * @param $token
     * @param array $param
     * @return bool|false|string
     */
    public static function tokenAuth($time, $token, array $param) {
        $timestamp_arr = Fun::getContentsByFile(self::TIMESTAMP_FILE);
        if ($timestamp_arr && in_array($time, $timestamp_arr)) {
            return false;
        }
        \S\Fun::write(self::TIMESTAMP_FILE, $time . "\n");
        $param_str = '';
        if (!empty($param)) {
            ksort($param);
            $param = array_values($param);
            $param_str = strtoupper(implode("", $param));
        }
        return substr(md5($time . '|' . $param_str . '|' . $token), 0, 18);
    }

}