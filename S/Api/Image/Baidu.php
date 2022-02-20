<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 20/4/4 上午12:05
 * Email: 18515831680@163.com
 *
 * 百度开放平台文字识别
 *
 */

namespace S\Api\Image;

use Config\Conf;

class Baidu extends Base {

    const ACCESS_TOKEN_URI = '/oauth/2.0/token'; //获取access_token
    const IDCARD_URI = '/rest/2.0/ocr/v1/idcard'; //身份证识别

    /**
     * 获取access_token
     * @return mixed
     * @throws \S\Exceptions
     * @throws \Exception
     */
    public function getAccessToken() {
        $config = Conf::getConfig("apps/image.baidu");
        $params = [
            'grant_type'    => 'client_credentials',
            'client_id'     => $config['api_key'],
            'client_secret' => $config['secret_key'],
        ];
        $url = $config['host'] . self::ACCESS_TOKEN_URI . '?' . http_build_query($params);

        $cache = new \App\Dao\Cache\Baidu();
        $ret = $cache->get($config['api_key']);
        $ret = json_decode($ret, true);
        if (! $ret) {
            $ret = \S\Http\Curl::request($url, 'POST');
            $ret = json_decode($ret, true);
            if (isset($ret['error'])) {
                throw new \S\Exceptions($ret['error_description']);
            }
            $cache->set($config['api_key'], json_encode($ret));
        }

        return $ret['access_token'];
    }

    /**
     * 身份证识别
     * @param string $image 二进制文件
     * @return bool|mixed|string
     * @throws \S\Exceptions
     * @throws \Exception
     */
    public function idCard($image) {
        $params = [
            'access_token' => $this->getAccessToken(),
        ];

        $host = Conf::getConfig('apps/image.baidu.host');
        $url = $host . self::IDCARD_URI . '?' . http_build_query($params);
        $request_params = [
            'image' => base64_encode($image),
            'id_card_side' => 'front',
        ];

        $ret = \S\Http\Curl::request($url, 'POST', $request_params);
        $ret = json_decode($ret, true);

        return $ret;
    }

}