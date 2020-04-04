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

class Baidu {

    const ACCESS_TOKEN_URL = 'https://aip.baidubce.com/oauth/2.0/token'; //access_token接口地址
    const IDCARD_URL = 'https://aip.baidubce.com/rest/2.0/ocr/v1/idcard'; //身份证识别

    /**
     * 获取access_token
     * @return mixed
     * @throws \S\Exceptions
     * @throws \Exception
     */
    public function getAccessToken() {
        $config = \Config\Conf::getConfig("apps/image.baidu");
        $params = [
            'grant_type'    => 'client_credentials',
            'client_id'     => $config['api_key'],
            'client_secret' => $config['secret_key'],
        ];
        $url = self::ACCESS_TOKEN_URL . '?' . http_build_query($params);

        $cache = new \App\Dao\Cache\Baidu();
        $ret = $cache->get($config['api_key']);
        $ret = json_decode($ret, true);
        if (! $ret) {
            $ret = \S\Curl::request($url, 'POST');
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
     * @return bool|mixed|string
     * @throws \S\Exceptions
     * @throws \Exception
     */
    public function idcard() {
        $params = [
            'access_token' => $this->getAccessToken(),
        ];

        $url = self::IDCARD_URL . '?' . http_build_query($params);
        $request_params = [
            'image' => base64_encode(file_get_contents("/www/tmp/image/WechatIMG48887.jpeg")),
            'id_card_side' => 'front',
        ];

        $ret = \S\Curl::request($url, 'POST', $request_params);
        $ret = json_decode($ret, true);

        return $ret;
    }

}