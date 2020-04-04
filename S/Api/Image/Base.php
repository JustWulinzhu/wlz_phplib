<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 20/4/4 下午11:05
 * Email: 18515831680@163.com
 */

namespace S\Api\Image;

abstract class Base {

    const CHANNEL_BAIDU = 'baidu';
    const CHANNEL_XUNFEI = 'xunfei';

    abstract function idCard($image);

}