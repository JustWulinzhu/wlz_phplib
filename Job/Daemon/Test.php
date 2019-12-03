<?php

namespace Job\Daemon;

use S\Log;

class Test implements \Job\Base
{

    public static $sleep_seconds = 2;

    /**
     * @param null $argv
     * @throws \Exception
     */
    public function exec($argv = null)
    {
        Log::getInstance()->debug(['111', date('Ymd H:i:s', time())]);
    }

}