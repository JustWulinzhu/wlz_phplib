<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 20/1/14 下午6:11
 * Email: 18515831680@163.com
 *
 * 大文件上传
 *
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
        $file = new Files();
        return $file->partUpload('/usr/local/mysql-5.7.27-linux-glibc2.12-i686.tar.gz', 'test');
    }

}