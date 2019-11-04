<?php
/**
 * Created by PhpStorm.
 * Author: wulinzhu
 * Email: linzhu.wu@beebank.com
 * Date: 18/7/12 下午4:23
 * redis锁机制,防止并发请求造成的超卖等问题
 * 参考mutexLock、transactionLock方法
 * 参考文献: http://www.36nu.com/post/314
 */

namespace Redis;

use \Redis\BaseRedis;

require_once dirname(__DIR__) . '/' . 'fun.php';

class Lock extends BaseRedis {

    private $redis;

    const EXPIRE_TIME = 15; //默认业务执行时间
    const DEFAULT_VALUE = 'default_value';
    const REDIS_LOCK = 'redis_lock_';

    /**
     * Lock constructor.
     * @throws \Exception
     */
    public function __construct() {
        $this->redis = $this->getInstance();
    }

    /**
     * 阻塞锁,轮询10次等待获取锁，获取失败返回false
     * @param $key
     * @return bool
     * @throws \Exception
     */
    public function blockLock($key) {
        $lock_ret = $this->mutexLock($key);
        if ($lock_ret) {
            return true;
        } else {
            $i = 0;
            while ($i < 10) { //轮询10次
                $lock_ret = $this->mutexLock($key);
                if ($lock_ret) {
                    return true;
                } else {
                    \Log::getInstance()->debug([__METHOD__, 'sleep', $key, $i]);
                    sleep(5); //等待5秒再次尝试获取锁
                    $i++;
                }
            }
            return false;
        }
    }

    /**
     * 互斥锁, set加nx参数实现
     *
     * ques1: 如果key过期了业务程序还没执行完成,会导致锁过期其他请求进来获得锁，发生错误。
     * 解决方案: （此方法采用方案2）
     * 方案1. 设置值为随机数,与下次请求进行对比;
     * 方案2. 设置key值为时间戳，当前时间+过期时间，每次请求进来时，判断现在当前时间与key值作大小比较，
     *       如果当前时间大于key值，说明此key已经过期，然后手动删除此key。
     *
     * 例：
     * $is_lock = (new Lock())->mutexLock('lock_id');
     * if ($is_lock) {
     *     //业务代码
     * } else {
     *     //直接返回，或者轮询等待获取锁
     * }
     *
     * @param $key
     * @param int $ttl
     * @return bool
     */
    public function mutexLock($key, $ttl = self::EXPIRE_TIME) {
        $locked_value = $this->redis->get($key);
        if ($locked_value && $locked_value < time()) {
            $this->unlock($key);
        }
        $ret = $this->redis->set(self::REDIS_LOCK . $key, time() + $ttl, array('nx', 'ex' => $ttl));
        return $ret ? true : false;
    }

    /**
     * 测试方法
     * 模拟剩余最后一个产品,2000并发抢购同一个产品,检查是否出现超卖情况。
     * 20191014验证通过
     * @param $key
     * @return bool
     * @throws \Exception
     */
    public function doLock($key) {
        $num = $this->redis->get(self::REDIS_LOCK . 'num');
        if ($num <= 0) {
            \Log::getInstance()->debug(array('无产品'));
            return false;
        }
        $ret = $this->mutexLock($key);
        if ($ret) {
            $this->redis->decr(self::REDIS_LOCK . 'num');
            \Log::getInstance()->debug(array('success'));
            $this->unlock($key);
            return true;
        } else {
            \Log::getInstance()->debug(array('fail'));
            $this->unlock($key);
            return false;
        }
    }

    /**
     * @param $key
     * @return bool
     * @throws \Exception
     */
    public function redisTransactionLock($key) {
        \Log::getInstance()->debug(array('request'));
        $user_id = mt_rand(1, 9000000000);
        $this->redis->watch($key);
        $this->redis->multi();
        $this->redis->set($key, $user_id);
        $res = $this->redis->exec();
        if ($res) {
            $mysql = new \Db('prize');
            $mysql2 = new \Db('user_prize');
            $count = count($mysql->select());
            $left_count = count($mysql->select(array('status' => 0), 'id'));
            $now_prize_id = $count - $left_count + 1;
            $mysql->update(array('status' => 1), array('id' => $now_prize_id));
            $mysql2->insert(array('user_id' => $user_id, 'prize_id' => $now_prize_id));
            if ($this->redis->exists($key)) {
                $this->unlock($key);
            }
            \Log::getInstance()->debug(array('success', $now_prize_id));
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