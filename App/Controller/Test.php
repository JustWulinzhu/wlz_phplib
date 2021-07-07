<?php

namespace App\Controller;

use S\Db;
use S\Office\Excel;
use S\Exceptions;
use S\Soap\Server;
use \S\Tools;
use S\Mail;
use S\Oss\Files;
use S\Queue\Redis\Redis;
use S\Url;
use S\Log;
use S\Http\Curl;
use S\Crypt\Aes;
use S\Crypt\Rsa;
use S\Oss\Oss;
use Config\Conf;
use S\Redis\Lock;
use S\Queue\Mns\Mns;
use S\Redis\BaseRedis as BaseRedis;
use S\Queue\Redis\Redis as QueueRedis;

class Test {

    /**
     * @param null $arr
     * @return string
     * @throws \Exception
     */
    public function index($arr = null) {
        $redis = (new \S\Redis\BaseRedis())->getInstance();
        $redis->set("ceshi", "xxxxxxxxx");
        $value = $redis->get("ceshi");
        dd($value);



        $a = '"2021052500000002"~"尹千万"~"Ind01"~"230221197506302610"~"CHN"~""~"1975/06/30"~"1"~"45"~""~""~""~"15727312197"~"2020/06/24"~""~""~"9"~""~""~""~"2"~"2"~""~1~""~""~"0"~""~"单位"~""~"U"~"500"~"04"~1~~~"40"~"01"~"0"~~~0~0~0~""~""~""~""~""~""~""~"2020/06/24"~""~""~""~""~""~""~""~""~""~""~""~""~""~""~~~""~""~""~""~"2021/05/01"~"fsed88"~"100000"~"2021/06/24"~"als88"~"100000"~""~""~""~""~"01"~"1"~""~""~"01"~"00000000"~"020"~"2"~""~""~""~""~"2020/06/24"~1~0~""~""~"2"~""~"0"~""~""~""~~~~~""~~""~""~""~""~""~';
        $b=explode("~", $a);
        dd($b);
        $a = date("Y/m/d",strtotime("+70years", strtotime('2008/06/06')));
//        $a = preg_match("/[0-9]{17}[Xx]/", '14012119910411111X');
        var_dump($a);die;
        $a = '"2021051900000011"~"郭巧琳"~"Ind01"~"230103196511025205"~""~"110100"~"19651102"~"2"~"56"~"10"~"10"~""~"13400103010"~""~"哈哈哈哈哈哈"~""~""~""~""~""~"2"~""~"9"~5~""~"9"~""~""~"顶焦度计很多很多"~""~"A"~""~""~1~8333.333333~1000~""~""~"0"~500000~~~~~""~""~""~"0"~"100000"~""~""~""~"哈哈哈哈吧"~"100000"~"100000"~""~""~""~""~""~""~""~""~""~""~""~600000~50000~""~""~""~""~"2021/05/01"~"610108"~"110174"~"2021/05/21"~"als88"~"100000"~""~""~""~""~"01"~""~""~""~""~""~""~""~""~""~""~""~""~3~~""~""~""~""~""~""~""~""~0~~~~""~5~""~""~"0"~""~"110100"~240000';
        $b = explode("~", $a);
        dd($b);
        dd(base64_encode('/NASEDOC/WEIXIN/MORTGAGE/IMAGE/20210114/220122199608153124/WX_11010_2021-01-14-15-35-01_04.jpeg'));

//        $soap = new \S\Soap\Server('\\App\\Controller\\Id', 'getUserInfo');
//        $soap->request();
//        die;

        $client = new \SoapClient("/www/image/Id.wsdl");
        $client->index();
        dd($client);


        $reg = '/^[\x{4e00}-\x{9fa5}0-9a-zA-Z()（）]+$/u';
        $str = '1啊啊啊a2222((()))（（（）-';
        var_dump(preg_match($reg, $str));die;
    }

    public function demo() {
        $ret = (new Db('url'))->select(['key' => 12345678]);
        return $ret;
    }

}