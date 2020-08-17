<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 20/8/17 下午4:31
 * Email: 18515831680@163.com
 *
 * 创建Sign请求参数
 *
 */

namespace Job\Jobs\Tmp;

class CreateSign implements \Job\Base
{

    /**
     * @param null $argv
     * @return mixed|void
     * @throws \OSS\Core\OssException
     * @throws \S\Exceptions
     */
    public function exec($argv = null)
    {
        $data = [
            'id' => '14012119910411415X',
        ];
        return \S\Sign::createSign($data);
    }

}