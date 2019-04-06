<?php
/**
 * Created by PhpStorm.
 * Author: wulinzhu
 * Email: linzhu.wu@beebank.com
 * Date: 18/12/15 上午1:04
 */
require_once('/www/wlz_phplib/fun.php');

$res = (new Queue())->push('test_queue', time());
Log::getInstance()->debug(array('push', (bool)$res));