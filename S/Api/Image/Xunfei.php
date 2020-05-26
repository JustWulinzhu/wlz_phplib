<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 20/4/4 下午3:27
 * Email: 18515831680@163.com
 *
 * 科大讯飞开放平台文字识别
 *
 */

namespace S\Api\Image;

use Config\Conf;

class Xunfei extends Base {

    const IDCARD_URI = '/v1/service/v1/ocr/idcard'; //身份证识别

    /**
     * 获取header头
     * @param array $param
     * @return array
     * @throws \Exception
     */
    private static function getHeader(array $param = array()) {
        $config = Conf::getConfig("apps/image.xunfei");
        $app_id = $config['app_id'];
        $api_key = $config['api_key'];
        $param = base64_encode(json_encode($param));
        $timestamp = time();

        $headers = [
            'X-Appid'       => $app_id,
            'APIKey'        => $api_key,
            'X-CurTime'     => $timestamp,
            'X-Param'       => $param,
            'X-CheckSum'    => self::getXCheckSum($api_key, $timestamp, $param),
        ];

        return $headers;
    }

    /**
     * @param $app_key
     * @param $timestamp
     * @param $params
     * @return string
     */
    private static function getXCheckSum($app_key, $timestamp, $params) {
        $params = is_array($params) ? base64_encode(json_encode($params)) : $params;
        return md5($app_key . $timestamp . $params);
    }

    /**
     * 身份证识别
     * @param string $image 二进制文件
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function idCard($image) {
        $param = ['engine_type' => 'idcard'];
        $headers = self::getHeader($param);

        $request_params = [
            'image' => base64_encode($image),
        ];

        $host = Conf::getConfig('apps/image.xunfei.host');
        $url = $host . self::IDCARD_URI;
        $ret = \S\Http\Guzzle::request($url, 'POST', $request_params, $headers);
        $ret = json_decode($ret, true);

        return $ret['data'];
    }

}