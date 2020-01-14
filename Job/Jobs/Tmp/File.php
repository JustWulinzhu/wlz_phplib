<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 20/1/14 下午6:11
 * Email: 18515831680@163.com
 */

namespace Job\Jobs\Tmp;

use S\Log;
use S\Oss\Files;

class File implements \Job\Base
{

    /**
     * @param null $argv
     * @return mixed|void
     * @throws \OSS\Core\OssException
     * @throws \S\Exceptions
     */
    public function exec($argv = null)
    {
        $ret = (new Files())->partUpload('/usr/local/mysql-5.6.45-linux-glibc2.12-x86_64.tar.gz', 'test');
        dd($ret);
    }

}