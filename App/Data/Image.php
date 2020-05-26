<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 20/4/4 下午9:03
 * Email: 18515831680@163.com
 */

namespace App\Data;

use S\Log;

class Image {

    const API_OCR_NAMESPACE = "\\S\\Api\\Image\\";

    /**
     * 三方渠道
     * @var array
     */
    private $channels = [
        \S\Api\Image\Base::CHANNEL_XUNFEI,
        \S\Api\Image\Base::CHANNEL_BAIDU,
    ];

    /**
     * 身份证识别
     * @param $image
     * @return mixed
     * @throws \Exception
     */
    public function idCard($image) {
        $ret = false;
        foreach ($this->channels as $channel) {
            $namespace = self::API_OCR_NAMESPACE . ucfirst($channel);
            $api = new $namespace;
            $ret = $api->idCard($image);
            if ($ret) {
                Log::getInstance()->debug([__CLASS__, __FUNCTION__, $channel, json_encode($ret)]);
                break;
            } else {
                Log::getInstance()->warning([__CLASS__, __FUNCTION__, $channel, json_encode($ret)]);
            }
        }
        if (! $ret) {
            throw new \S\Exceptions('身份证识别失败');
        }

        return $ret;
    }

}