<?php
/**
 * Created by PhpStorm.
 * Author: wulinzhu
 * Email: linzhu.wu@beebank.com
 * Date: 19/9/23 下午6:57
 * redis基类
 */

require_once dirname(__DIR__) . '/' . 'fun.php';

class BaseRedis {

    private $redis = null;

    /**
     * 获取redis实例
     * @return null|Redis
     * @throws Exception
     */
    public function getInstance() {
        $conf = Conf::getConfig('redis/db1');
        if (is_null($this->redis)) {
            $this->redis = new Redis();
            try {
                $this->redis->connect($conf['host'], $conf['port']);
                //判断redis connect是否连接成功
                $this->redis->ping();
            } catch (Exception $e) {
                Log::getInstance()->error(array('redis链接失败', $e->getMessage(), $e->getCode()), 'exceptions');
                throw new Exception('redis链接失败-' . $e->getMessage(), $e->getCode());
            }
        }
        return $this->redis;
    }

}