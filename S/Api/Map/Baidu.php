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
        if ($ret['status'] != 0) {
            $ret = [
                'status' => $ret['status'],
                'msg' => $this->errors($ret['status']),
            ];
        }

        return $ret;
    }

    /**
     * 错误码翻译
     * @param $code
     * @return mixed|string
     */
    public function errors($code) {
        $code_map = [
            0	=> '正常',
            1	=> '服务器内部错误',
            10	=> '上传内容超过8M',
            101	=> 'AK参数不存在',
            102	=> 'Mcode参数不存在，mobile类型mcode参数必需',
            200	=> 'APP不存在，AK有误请检查再重试',
            201	=> 'APP被用户自己禁用，请在控制台解禁',
            202	=> 'APP被管理员删除',
            203	=> 'APP类型错误',
            210	=> 'APP',
            211	=> 'APP',
            220	=> 'APP',
            230	=> 'APP',
            240	=> 'APP',
            250	=> '用户不存在',
            251	=> '用户被自己删除',
            252	=> '用户被管理员删除',
            260	=> '服务不存在',
            261	=> '服务被禁用',
            301	=> '永久配额超限，限制访问',
            302	=> '天配额超限，限制访问',
            401	=> '当前并发量已经超过约定并发配额，限制访问',
            402	=> '当前并发量已经超过约定并发配额，并且服务总并发量也已经超过设定的总并发配额，限制访问',
            1001 => '没有IPv6地址访问的权限',
        ];

        if (isset($code_map[$code])) {
            return $code_map[$code];
        }

        return '系统异常';
    }

}