<?php
/**
 * Created by PhpStorm.
 * Author: wulinzhu
 * Email: linzhu.wu@beebank.com
 * Date: 18/7/12 下午4:23
 * redis锁机制,防止并发请求造成的超卖等问题
 * 参考lock、transactionLock方法
 * 参考文献: http://www.36nu.com/post/314
 */

require_once dirname(__DIR__) . '/' . 'fun.php';

class Cache extends BaseRedis {

    private $redis;

    const EXPIRE_TIME = 10; //默认业务执行时间
    const DEFAULT_VALUE = 'default_value';
    const REDIS_CACHE = 'redis_cache_';

    /**
     * Cache constructor.
     */
    public function __construct() {
        $this->redis = $this->getInstance();
    }

    /**
     * set加nx参数实现锁机制
     * ques1: 如果处理时间比业务执行时间还长,会导致锁过期然后其他请求进来,解决方案,设置值为随机数,与下次请求进行对比,本方法暂不考虑此问题
     * @param $key
     * @param $value
     * @param int $ttl
     * @return bool
     */
    public function lock($key, $value, $ttl = self::EXPIRE_TIME) {
        $ret = $this->redis->set(self::REDIS_CACHE . $key, $value, array('nx', 'ex' => $ttl));
        if ($ret) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 测试方法
     * 模拟剩余最后一个产品,2000并发抢购同一个产品,检查是否出现超卖情况。
     * 20191014验证通过
     * @param $key
     * @param $value
     * @return bool
     */
    public function doLock($key, $value) {
        $num = $this->redis->get(self::REDIS_CACHE . 'num');
        if ($num <= 0) {
            Log::getInstance()->debug(array('无产品'));
            return false;
        }
        $ret = $this->lock($key, $value);
        if ($ret) {
            $this->redis->decr(self::REDIS_CACHE . 'num');
            Log::getInstance()->debug(array('success'));
            $this->unlock($key);
            return true;
        } else {
            Log::getInstance()->debug(array('fail'));
            $this->unlock($key);
            return false;
        }
    }

    /**
     * @param $key
     * @return bool
     */
    public function redisTransactionLock($key) {
        Log::getInstance()->debug(array('request'));
        $user_id = mt_rand(1, 9000000000);
        $this->redis->watch($key);
        $this->redis->multi();
        $this->redis->set($key, $user_id);
        $res = $this->redis->exec();
        if ($res) {
            $mysql = new Db('prize');
            $mysql2 = new Db('user_prize');
            $count = count($mysql->select());
            $left_count = count($mysql->select(array('status' => 0), 'id'));
            $now_prize_id = $count - $left_count + 1;
            $mysql->update(array('status' => 1), array('id' => $now_prize_id));
            $mysql2->insert(array('user_id' => $user_id, 'prize_id' => $now_prize_id));
            if ($this->redis->exists($key)) {
                $this->unlock($key);
            }
            Log::getInstance()->debug(array('success', $now_prize_id));
            return true;
        }
        return false;
    }

    /**
     * 释放锁
     * @param $key
     * @return int
     */
    public function unlock($key) {
        return $this->redis->del($key);
    }

}

$redis = new Cache();
$ret = $redis->doLock('test', 100);
if ($ret) {
    echo 'success';
} else {
    echo 'fail';
}