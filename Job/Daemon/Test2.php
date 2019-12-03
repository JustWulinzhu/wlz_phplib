<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 19/12/3 下午4:57
 * Email: 18515831680@163.com
 */

namespace Job\Daemon;

use S\Log;

class Test2 implements \Job\Base
{
    public static $sleep_seconds = 5;

    /**
     * @param null $argv
     * @throws \Exception
     */
    public function exec($argv = null)
    {
        Log::getInstance()->debug(['222', date('Ymd H:i:s', time())]);
    }

}