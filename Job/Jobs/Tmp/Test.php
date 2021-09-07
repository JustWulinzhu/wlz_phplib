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
        for ($i = 100001; $i <= 300000; $i++) {
            (new Db("test1"))->insert([
                'id2' => $i,
                'id3' => $i + 1,
                'name' => md5(random_int(0, 10000)),
            ]);
        }
    }

}