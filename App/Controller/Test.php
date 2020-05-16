<?php

namespace App\Controller;

use S\Db;
use S\Office\Excel;
use S\Exceptions;
use \S\Tools;
use S\Mail;
use S\Oss\Files;
use S\Queue\Redis\Redis;
use S\Url;
use S\Log;
use S\Curl;
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
     * @return mixed
     * @throws \Exception
     */
    public function index($arr = null) {

        $start_date = date('Y/m/d', strtotime('20200511'));
        $stop_date  = date("Y/m/d", strtotime("-1 day", strtotime("+1 year", strtotime($start_date))));
        dd($stop_date);

        $ret =Tools::numberToChinese('5000');
        dd($ret);

        header("Content-type: application/pdf");
        readfile('/tmp/CONTRACT_BC2020050400000001.pdf');

        $a = strtoupper('20200430_5eaa94dcadcda');
        dd($a);

        $str = "{\"applyNo\":\"20200319100029\",\"applyAmount\":\"5000\",\"contactMobile\":\"17600716831\",\"mobile\":\"17600716831\",\"certNo\":\"230126199205293226\",\"name\":\"\\u5f20\\u4e09\",\"monthFee\":\"79.00\",\"months\":\"12\"}";

        $en = base64_encode(openssl_encrypt($str, 'AES-128-ECB', '90ee389a118bf84e', OPENSSL_RAW_DATA, ''));

        $enplain = 'AfOPMUdg1mG4Wwqe7SrGlZVerVvJUsw19ulicQKox0HPw+r2c4enqPEoa2jB00uxqdYa04CKDL30U/UOI5h6I/cE82nLQNheL0xDbsftXGuiWAZFJOOZeyXrfnE+NQtzWD/vvr26aSZESCD2dVHs1IQp3pIj8u8/sZ/hcBbnF3pP94tFyWO8WjcB3oHvzcIRoD/WlvyR/aTo6tfJoDVSxLjwK9ibAkOQWZsSt29Jj8mohVGS19vDJdfIMIS3dSXF';
        $enplain = str_replace(array('-','_'),array('+','/'),$enplain);
        $enplain = base64_decode($enplain);
        $plain = openssl_decrypt($enplain, 'AES-128-ECB', '90ee389a118bf84e', OPENSSL_RAW_DATA);

        print_r($en);die;
    }

    public function urlsafe_b64encode($string) {
        $data = base64_encode($string);
        $data = str_replace(array('+','/','='),array('-','_',''),$data);
        return $data;
    }


    public function urlsafe_b64decode($string) {
        $data = str_replace(array('-','_'),array('+','/'),$string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }

}