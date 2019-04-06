<?php
/**
 * Created by PhpStorm.
 * Author: wulinzhu
 * Email: linzhu.wu@beebank.com
 * Date: 18/7/12 下午4:23
 * redis锁机制,防止并发请求造成的超卖等问题
 * 参考lock、transactionLock方法
 */
include_once "fun.php";
class RedisLock {

    private $redis;

    const EXPIRE_TIME = 5; //预留3s的业务代码执行时间
    const DEFAULT_VALUE = 'default_value'; //预留3s的业务代码执行时间

    function __construct($config) {
        $this->redis = new Redis();
        $this->connect($config);
    }

    public function lock2($key, $expire_time = self::EXPIRE_TIME) {
        $is_lock = $this->redis->setnx($key, time() + $expire_time);
        if ($is_lock) {

            $this->unlock($key);
        }
        if ($this->redis->exists($key) && $this->redis->get($key) < time()) {
            $this->unlock($key);
        }
    }

    /**
     * 2018年8月1号测试成功,阿里云接口压测,并发80,总请求量35000条,成功率99.89%,产生数据100%正确。
     * 2018年8月2号测试失败,产生大概1%脏数据
     * 获取锁示例
     * @param $key
     * @param int $expire_time
     * @return bool
     */
    public function lock($key, $expire_time = self::EXPIRE_TIME) {
        $user_id = mt_rand(1, 9000000000);

        if ($this->redis->exists($key) && ($this->redis->ttl($key) > 0) && $this->redis->get($key) < time()) { //防止死锁,如果执行完业务代码后因为某些原因释放锁失败,利用过期时间进行判断,再次释放锁
            $this->unlock($key);
        }
        $is_lock = $this->redis->setnx($key, time() + $expire_time);
        if ($is_lock) {
            $this->redis->expire($key, $expire_time);
            //业务代码,逻辑操作等等,可以先写入redis然后在更新数据库
            $mysql = new Mysql('prize');
            $mysql2 = new Mysql('user_prize');
            $count = count($mysql->select());
            $left_count = count($mysql->select(array('status' => 0), 'id'));
            $now_prize_id = $count - $left_count + 1;
            Log::getInstance()->debug(array($now_prize_id));
            $mysql->update(array('status' => 1), array('id' => $now_prize_id));
            $mysql2->insert(array('user_id' => $user_id, 'prize_id' => $now_prize_id));
            if ($this->redis->ttl($key) > 0) {
                $this->unlock($key);
            }
            return false;
        }
        return $is_lock ? true : false;
    }

    /**
     * 利用set参数直接实现setnx加锁效果
     * @param $key
     * @param string $value
     * @param int $expire_time
     * @return bool
     */
    public function redisLock($key, $value = self::DEFAULT_VALUE, $expire_time = self::EXPIRE_TIME) {
        $lock = $this->redis->set($key, $value, 'ex', $expire_time, 'nx');
        return $lock ? true : false;
    }

    /**
     * 执行成功后进行后续操作更新数据库等等,一次操作完成之后记得调用$this->unlock()去释放锁
     * @param string $key
     * @param int $value
     * @param int $expire
     * @return bool
     */
    public function transactionLock($key, $value = 1, $expire = 0) {
        $this->redis->watch('redis_lock_key' . $key);
        $this->redis->multi();
        if ($expire) {
            $this->redis->set($key, $value, $expire);
        } else {
            $this->redis->set($key, $value);
        }
        $res = $this->redis->exec();
        return $res ? true : false;
    }

    /**
     * 2018年8月1号测试失败,原因未确定,猜测是并发导致数据穿透数据库,操作完redis之后相同的数据再次进入导致,解决方案:请求一进来就加锁,防止并发穿透数据库。
     * 互斥锁
     * 同时两个请求来的时候一个请求执行成功,另一个会被取消
     * @param $user_id
     * @return string
     */
    public function redisTransactionLock($user_id) {
        //抢购活动一个用户抢一个,一个user_id对应一个奖品id
        //例如100个商品,1000个人同时抢购,如何避免库存超卖的问题和两个用户抢到同一个商品的问题。
        //方法一:$this->lock();
        //方法二:本方法,事务加watch
        $mysql = new Mysql('prize');
        $mysql2 = new Mysql('user_prize');
        //商品总数
        $list = $mysql->select(array(), array('id', 'prize_key'));
        $all_count = count($list);
        //剩下多少个未被抢购的
        $left = $mysql->select(array('status' => 0), 'id');
        $left_count = count($left);
        if ($left_count <= 0) return false;
        $now_prize_id = $all_count - $left_count + 1;

        $key = 'redis_left_num';
        $this->redis->set($key, $now_prize_id);
        $this->redis->watch($key);
        $this->redis->multi();
        $this->redis->decr($key);
        $res = $this->redis->exec();
        if ($res) {
            //更新mysql,记录得奖用户和得奖的奖品id
            $mysql->update(array('status' => 1), array('id' => $now_prize_id));
            $mysql2->insert(array('user_id' => $user_id, 'prize_id' => $now_prize_id));
            $this->unlock($key);
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $key
     * @return bool
     */
    public function redisTransactionLock2($key) {
        Log::getInstance()->debug(array('request'));
        $user_id = mt_rand(1, 9000000000);
        $this->redis->watch($key);
        $this->redis->multi();
        $this->redis->set($key, $user_id);
        $res = $this->redis->exec();
        if ($res) {
            $mysql = new Mysql('prize');
            $mysql2 = new Mysql('user_prize');
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
     * 2018年8月6号测试成功,添加500条任务到队列中
     * 添加任务队列
     * @param $user_id
     * @return bool|int
     */
    public function pushQueue($user_id) {
        $queue_key = 'queue_key';
        if ($this->redis->exists($queue_key) && $this->redis->lLen($queue_key) >= 500) {
            Log::getInstance()->debug(array('beyond 500'));
            return false;
        }
        Log::getInstance()->debug(array('user_id', $user_id));
        if ($this->redis->setnx('redis-lock', self::EXPIRE_TIME + time())) {
            $this->redis->lPush($queue_key, $user_id);
            $this->unlock('redis-lock');
        }
        if ($this->redis->exists('redis-lock') && $this->redis->get('redis-lock') < time()) {
            Log::getInstance()->debug(array('unlock failed'));
            $this->unlock('redis-lock');
        }
    }

    /**
     * 消费队列
     * @return bool
     */
    public function consumeQueue() {
        $queue_key = 'queue_key';
        while ($this->redis->lLen($queue_key) > 0 && $this->redis->exists($queue_key)) {
            $value = $this->redis->rPop($queue_key);
            Log::getInstance()->debug(array('consumeQueue user_id', $value));
            if ($value) {
                $mysql = new Mysql('prize');
                $mysql2 = new Mysql('user_prize');
                $count = count($mysql->select());
                $left_count = count($mysql->select(array('status' => 0), 'id'));
                $now_prize_id = $count - $left_count + 1;
                $mysql->update(array('status' => 1), array('id' => $now_prize_id));
                $mysql2->insert(array('user_id' => $value, 'prize_id' => $now_prize_id));
            }
        }
        return true;
    }

    /**
     * 释放锁
     * @param $key
     * @return int
     */
    public function unlock($key) {
        return $this->redis->del($key);
    }

    /**
     * @param $config
     * @return bool
     */
    private function connect($config) {
        $conn = $this->redis->connect($config['host'], $config['port']);
        return $conn ? true : false;
    }


}
$redis = new RedisLock(array('host' => 'localhost', 'port' => 6379));
//$res = $redis->redisTransactionLock2('redis-lock');
//$res = $redis->lock('redis-lock');
//$res = $redis->consumeQueue(mt_rand(1, 99999999));
//var_dump($res);