<?php

require_once dirname(__DIR__) . '/' . 'fun.php';

class Frep extends BaseRedis {

    const FREQ = 'freq';

    const FREQ_TYPE_MINUTE = 60; //按每分钟计数
    const FREQ_TYPE_HOUR = 3600; //按每小时计数
    const FREQ_TYPE_DAY = 86400; //按每天计数
    const FREQ_TYPE_FOREVER = 1; //永久计数

    private $_redis = null;

    public function __construct() {
        $this->_redis = $this->getInstance();
    }

}