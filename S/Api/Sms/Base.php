<?php
/**
 * Created by PhpStorm.
 * Author: wulinzhu
 * Email: linzhu.wu@beebank.com
 * Date: 21/7/16 下午6:19
 */

namespace S\Api\Sms;

interface Base {

    /**
     * 定义短信发送接口规范
     *
     * @param $mobile
     * @param $tpl_id
     * @param $params
     * @return mixed
     */
    public function send($mobile, $tpl_id, $params);

}