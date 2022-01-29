<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 20/3/13 下午3:33
 * Email: 18515831680@163.com
 *
 * 测试脚本
 * php /data1/www/wlz_phplib/Job/Job.php Job_Jobs_Tmp_Test
 *
 */

namespace Job\Jobs\Tmp;

use S\Db;
use \S\Tools;
use S\Log;

class Test implements \Job\Base
{

    /**
     * @param null $argv
     * @return mixed|void
     * @throws \Exception
     */
    public function exec($argv = null)
    {
        for ($i = 1; $i<=10;  $i++) {
            file_get_contents("/root/jdk-16.0.2_linux-aarch64_bin.tar.gz");
            sleep(1);
        }
    }

}