<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 20/4/4 上午12:53
 * Email: 18515831680@163.com
 */

namespace App\Dao\Cache;

class Baidu {

    const PRE_KEY = 'baidu_app_access_token_';

    const TTL = 86400;

    /**
     * 获取redis实例
     * @return \Redis|null
     * @throws \Exception
     */
    public function getInstance() {
        return (new \S\Redis\BaseRedis())->getInstance();
    }

    /**
     * @param $key
     * @return bool|mixed|string|null
     * @throws \Exception
     */
    public function get($key) {
        return $this->getInstance()->get($key);
    }

    /**
     * @param $key
     * @param $value
     * @param int $ttl
     * @return bool
     * @throws \Exception
     */
    public function set($key, $value, $ttl = self::TTL) {
        return $this->getInstance()->set($key, $value, $ttl);
    }

}