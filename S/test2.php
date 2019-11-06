<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 19/11/6 下午3:26
 * Email: 18515831680@163.com
 */

namespace S;

use S\Db;

class Test2 {

    /**
     * @return array
     * @throws \Exception
     */
    public function test() {
        return (new Db('sl'))->select(['id'=>1]);
    }

}

$ret = (new Test2())->test();
print_r($ret);