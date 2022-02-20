<?php
/**
 * Created by PhpStorm.
 * Author: wulinzhu
 * Email: linzhu.wu@beebank.com
 * Date: 21/7/16 上午10:59
 *
 * 投石科技 短信接口
 *
 */

namespace S\Api\Sms;

use Config\Conf;
use S\Log;

class Tskj implements Base {

    const SMS_SEND_URI = '/ts/notifySms';

    /**
     * 发送短信
     *
     * @param $mobile
     * @param $tpl_id
     * @param $params
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function send($mobile, $tpl_id, $params) {
        $config = Conf::getConfig('apps/sms.tskj');

        $url = $config['host'] . self::SMS_SEND_URI;
        $header = [
            'Authorization' => 'APPCODE ' . $config['appcode'],
        ];
        $params_str = '';
        foreach ($params as $key => $value) {
            $params_str .= $key . ':' . $value;
        }
        $params = [
            'mobile' => $mobile,
            'param' => $params_str,
            'tpl_id' => $tpl_id,
        ];
        Log::getInstance()->debug([__METHOD__, 'sms_request', $url, json_encode($params)]);
        $ret = \S\Http\Guzzle::request($url, 'POST', $params, $header);
        Log::getInstance()->debug([__METHOD__, 'sms_response', $ret]);

        return $ret;
    }

}