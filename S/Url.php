<?php
/**
 * Created by PhpStorm.
 * Author: wulinzhu
 * Email: 18515831680@163.com
 * Date: 19/10/18 下午6:59
 * 短网址生成类 规则: 缩短网址长度, 生成短网址, 数据库建立映射关系。
 * 短网址生成规则: 数字加字母大小写62位, 采用6位62进制数字, 逐位累加。优点: 不会重复。缺点: 自增式id, 有可能被发现规律, 可以改为每次累加10等非1数字,打乱规律。
 */

namespace S;

use S\Db;

class Url {

    private static $num_start = 62*15018571+1; //62进制6位开始数字

    /**
     * 生成短网址
     * @param $url
     * @return mixed
     * @throws \Exception
     */
    public static function createShortUrl($url) {
        $db = new Db('url');

        if ($db->select(['url' => $url])) {
            throw new \Exception('网址已存在');
        }

        $sql = "select * from url order by id desc limit 1";
        $ret = $db->queryoneSql($sql);

        $db->transaction();
        if (empty($ret)) {
            $data = array(
                'key' => self::$num_start,
                'url' => $url,
                'short_url' => Tools::numTransform(self::$num_start),
            );
            if (! $db->insert($data)) {
                $db->rollBack();
                throw new \Exception(__FUNCTION__ . '-' . __LINE__ .  ' 数据插入失败 !');
            }
            $short_url = $data['short_url'];
        } else {
            $data = array(
                'key' => $ret['key'] + 1,
                'url' => $url,
                'short_url' => Tools::numTransform($ret['key'] + 1),
            );
            if (! $db->insert($data)) {
                throw new \Exception(__FUNCTION__ . '-' . __LINE__ . ' 数据插入失败 !');
            }
            $short_url = $data['short_url'];
        }
        $db->commit();

        return $short_url;
    }

    /**
     * 获取原网址
     * @param $short_url
     * @return mixed
     * @throws \Exception
     */
    public static function getUrl($short_url) {
        $db = new Db('url');
        $ret = $db->queryone(['short_url' => $short_url]);
        if (empty($ret)) {
            throw new \Exception('网址不存在');
        }
        return $ret['url'];
    }

}