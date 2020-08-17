<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 20/8/17 下午4:02
 * Email: 18515831680@163.com
 *
 * 根据身份证号查询地址
 *
 */

namespace App\Controller;

class Id extends \App\Controller\Base
{

    protected $verify = true;

    /**
     * @param $argv
     * @return string
     * @throws \Exception
     */
    public function index($argv)
    {
        $id = $argv['id'];
        if (empty($id)) {
            throw new \S\Exceptions('缺少身份证号');
        }
        if (! preg_match("/^[0-9]{17}[0-9xX]{1}$/", $id)) {
            throw new \S\Exceptions('身份证格式错误');
        }

        return (new \App\Data\Id())->getAddrById($id);
    }

}