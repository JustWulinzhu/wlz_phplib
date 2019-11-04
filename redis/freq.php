<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 19/10/31 下午2:13
 * Email: 18515831680@163.com
 *
 * redis频率限制器
 *
 * 只能采用本类定义时间去创建频率限制器，分、时、天、周、月、永久
 *
 * 默认每次累加1，默认超出10次限制访问
 *
 * 例：给id为operation1操作添加每天访问20次的限制，超出20次报错。
 *
 * $freq = new Freq();
 * if ($freq->check('operation1', Frep::FREQ_TYPE_DAY, 20)) {
 *      //超出频率限制
 *      return false;
 * }
 *
 * if (触发累加程序) {
 *      $freq->incr('operation1', Frep::FREQ_TYPE_DAY);
 * }
 *
 * 删除频率限制key
 * $freq->clear('operation1', Frep::FREQ_TYPE_DAY);
 *
 */

namespace Redis;

use \Redis\BaseRedis;

require_once dirname(__DIR__) . '/' . 'fun.php';

class Frep extends BaseRedis
{

    const FREQ = 'freq_';
    const DEFAULT_ADD_NUM = 1;
    const DEFAULT_LIMIT = 10;

    const FREQ_TYPE_MINUTE_TTL = 60; //按每分钟计数
    const FREQ_TYPE_HOUR_TTL = 3600; //按每小时计数
    const FREQ_TYPE_DAY_TTL = 86400; //按每天计数
    const FREQ_TYPE_WEEK_TTL = 604800; //按每周计数
    const FREQ_TYPE_MONTH_TTL = 2592000; //按每月计数
    const FREQ_TYPE_FOREVER_TTL = 3110400000; //永久计数

    const FREQ_TYPE_MINUTE = 'type_minute';
    const FREQ_TYPE_HOUR = 'type_hour';
    const FREQ_TYPE_DAY = 'type_day';
    const FREQ_TYPE_WEEK = 'type_week';
    const FREQ_TYPE_MONTH = 'type_month';
    const FREQ_TYPE_FOREVER = 'type_forever';

    public static $freq_type = array(
        self::FREQ_TYPE_MINUTE,
        self::FREQ_TYPE_HOUR,
        self::FREQ_TYPE_DAY,
        self::FREQ_TYPE_WEEK,
        self::FREQ_TYPE_MONTH,
        self::FREQ_TYPE_FOREVER,
    );

    private static $_redis = '';

    /**
     * Frep constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        self::$_redis = $this->getInstance();
    }

    /**
     * 获取key
     * @param $key
     * @return string
     */
    public static function getKey($key)
    {
        return self::FREQ . $key;
    }

    /**
     * 检测是否超阈值 true->超出限制，false->未超出
     * @param $hash_key
     * @param string $hash_field
     * @param int $limit
     * @return bool
     * @throws \Exception
     */
    public function check($hash_key, $hash_field = self::FREQ_TYPE_MINUTE, $limit = self::DEFAULT_LIMIT)
    {
        self::checkFrepType($hash_field);
        $ret = self::$_redis->hGet(self::getKey($hash_key), $hash_field);
        return ($ret > $limit);
    }

    /**
     * 累加计数 默认每次加1
     * @param $hash_key
     * @param string $hash_field
     * @param int $value
     * @return int
     * @throws \Exception
     */
    public function incr($hash_key, $hash_field = self::FREQ_TYPE_MINUTE, $value = self::DEFAULT_ADD_NUM)
    {
        self::checkFrepType($hash_field);
        $ret = self::$_redis->hIncrBy(self::getKey($hash_key), $hash_field, $value);
        self::$_redis->expire(self::getKey($hash_key), self::getTTL($hash_field));
        return $ret;
    }

    /**
     * 删除
     * @param $hash_key
     * @param $hash_field
     * @return false|int
     */
    public function clear($hash_key, $hash_field) {
        return self::$_redis->hDel($hash_key, $hash_field);
    }

    /**
     * 检查是否是支持的频率类型
     * @param $hash_field
     * @return bool
     * @throws \Exception
     */
    public static function checkFrepType($hash_field)
    {
        if (in_array($hash_field, self::$freq_type)) {
            return true;
        }
        throw new \Exception('不存在的频率类型！');
    }

    /**
     * @param $freq_type
     * @return false|int
     * @throws \Exception
     */
    public static function getTTL($freq_type)
    {
        switch ($freq_type) {
            case self::FREQ_TYPE_MINUTE :
                $ttl = self::FREQ_TYPE_MINUTE_TTL;
                break;
            case self::FREQ_TYPE_HOUR :
                $ttl = self::FREQ_TYPE_HOUR_TTL;
                break;
            case self::FREQ_TYPE_DAY :
                $ttl = \Fun::getLeftSeconds();
                break;
            case self::FREQ_TYPE_WEEK :
                $ttl = \Fun::getLeftSeconds('week');
                break;
            case self::FREQ_TYPE_MONTH :
                $ttl = \Fun::getLeftSeconds('month');
                break;
            case self::FREQ_TYPE_FOREVER :
                $ttl = self::FREQ_TYPE_FOREVER_TTL;
                break;
            default :
                throw new \Exception('freq_type_error');
        }
        return $ttl;
    }

}