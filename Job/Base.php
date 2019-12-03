<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 19/11/13 下午6:24
 * Email: 18515831680@163.com
 *
 * cli模式接口规范
 * 默认脚本文件必须继承该类，且实现该类中的exec方法
 *
 */

namespace Job;

interface Base {

    /**
     * cli模式默认执行方法
     * @param $argv
     * @return mixed
     */
    public function exec($argv = null);

}