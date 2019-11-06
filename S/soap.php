<?php
/**
 * Created by PhpStorm.
 * Author: wulinzhu
 * Email: linzhu.wu@beebank.com
 * Date: 19/10/27 下午4:55
 */

namespace S;

class Soap
{

    /**
     * @param $url
     * @return bool
     */
    public static function request($url) {
        try {
            $soapClient = new \SoapClient($url);
        } catch (\Exception $e) {

        }
        return true;
    }

}