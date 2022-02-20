<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 20/7/17 上午10:23
 * Email: 18515831680@163.com
 */

namespace App\Data;

class Map {

    /**
     * @param $ip
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function getCityByIp($ip) {
        $baidu = new \S\Api\Map\Baidu();
        try {
            $ret = $baidu->getLocationByIp($ip);
            if (0 != $ret['status']) {
                throw new \Exception('ip定位失败');
            }
        } catch (\Exception $e) {
            $ret = $e->getMessage();
        }

        return is_array($ret) ? $ret['content']['address'] : $ret;
    }

}