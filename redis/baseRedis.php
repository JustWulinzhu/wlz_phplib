<?php
/**
 * Created by PhpStorm.
 * Author: wulinzhu
 * Email: linzhu.wu@beebank.com
 * Date: 19/9/23 下午6:57
 * redis基类
 */

class BaseRedis {

    private $redis = null;

    /**
     * 获取redis实例
     * @return null|Redis
     * @throws Exceptions
     */
    protected function getInstance() {
        $conf = Conf::getConfig('redis/db1');
        if (is_null($this->redis)) {
            $this->redis = new Redis();
            try {
                $this->redis->connect($conf['host'], $conf['port']);
            } catch (Exceptions $e) {
                throw new Exceptions('redis链接失败-' . $e->getMessage(), $e->getCode());
            }
        }
        return $this->redis;
    }

}