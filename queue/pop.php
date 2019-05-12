<?php
/**
 * Created by PhpStorm.
 * Author: wulinzhu
 * Email: linzhu.wu@beebank.com
 * Date: 18/12/15 上午1:07
 */
require_once('/www/wlz_phplib/fun.php');

$res = (new Queue())->pop('test_queue');
Log::getInstance()->debug(array('pop', (bool)$res));