<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 20/7/17 上午9:56
 * Email: 18515831680@163.com
 *
 * 百度地图开放平台文档
 * ip定位：http://lbsyun.baidu.com/index.php?title=webapi/ip-api
 *
 */

namespace S\Api\Map;

use Config\Conf;

class Baidu {

    /**
     * ip定位
     * @param $ip
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function getLocationByIp($ip) {
        $config = Conf::getConfig('apps/map.baidu');
        $params = [
            'ak' => $config['ak'],
            'ip' => $ip,
            'coor' => 'bd09ll',
        ];
        $url = $config['host'] . '?' . http_build_query($params);
        $ret = \S\Http\Guzzle::request($url);
        $ret = json_decode($ret, true);

        return $ret;
    }

}