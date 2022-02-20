<?php
/**
 * Created by PhpStorm.
 * Author: wulinzhu
 * Email: 18515831680@163.com
 * Date: 19/9/23 下午6:57
 *
 * redis基类 支持单机、集群模式
 */

namespace S\Redis;

use Config\Conf;
use S\Log;

class BaseRedis {

    const REDIS_MOD_CLUSTER = "cluster";
    const REDIS_MOD_SINGLE = "single";

    private $redis = null;

    /**
     * 获取redis实例
     *
     * @param string $type
     * @return \Redis|\RedisCluster|null
     * @throws \Exception
     */
    public function getInstance($type = self::REDIS_MOD_CLUSTER) {
        if (self::REDIS_MOD_CLUSTER != $type && self::REDIS_MOD_SINGLE != $type) {
            throw new \Exception("错误的redis模式");
        }

        return self::REDIS_MOD_CLUSTER == $type ? $this->cluster() : $this->single();
    }

    /**
     * redis集群
     *
     * @return \RedisCluster
     * @throws \Exception
     */
    private function cluster() {
        if (is_null($this->redis) || ! $this->redis instanceof \RedisCluster) {
            try {
                $hosts = Conf::getConfig('redis/cluster');
                $this->redis = new \RedisCluster(null, $hosts);
            } catch (\Exception $e) {
                Log::getInstance()->error(["redis集群链接失败", $e->getMessage(), $e->getCode()]);
                throw new \Exception("redis集群链接失败" . $e->getMessage(), $e->getCode());
            }
        }

        return $this->redis;
    }

    /**
     * redis单机
     *
     * @return \Redis|null
     * @throws \Exception
     */
    private function single() {
        $conf = Conf::getConfig('redis/single');
        if (is_null($this->redis) || ! $this->redis instanceof \Redis) {
            $this->redis = new \Redis();
            try {
                $this->redis->connect($conf['host'], $conf['port']);
                //判断redis connect是否连接成功
                $this->redis->ping();
            } catch (\Exception $e) {
                Log::getInstance()->error(array('redis单机链接失败', $e->getMessage(), $e->getCode()), 'exceptions');
                throw new \Exception('redis链接失败-' . $e->getMessage(), $e->getCode());
            }
        }

        return $this->redis;
    }

}