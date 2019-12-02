<?php

namespace Job\Daemon;

use S\Log;

class Test implements \Job\Base
{

    /**
     * @param null $argv
     * @throws \Exception
     */
    public function exec($argv = null)
    {
        Log::getInstance()->debug(['doTask', date('Ymd H:i:s', time())]);
    }

}