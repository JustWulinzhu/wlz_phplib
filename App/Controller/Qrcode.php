<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 20/6/3 下午5:35
 * Email: 18515831680@163.com
 *
 * 二维码生成入口
 *
 */

namespace App\Controller;

class Qrcode extends \App\Controller\Base {

    protected $verify = false;

    /**
     * @param $argv
     * @throws \S\Exceptions
     */
    public function index($argv) {
        $text = $argv['text'];
        if (! $text) {
            throw new \S\Exceptions('text参数为空');
        }
        $ret = \S\Qrcode::create($text, '/www/tmp/image/gou.png');
        (new \S\Image())->show(base64_encode(file_get_contents($ret)));
    }

}