<?php
/**
 * Created by PhpStorm.
 * Author: wulinzhu
 * Email: linzhu.wu@beebank.com
 * Date: 18/12/14 下午5:13
 */
header("content-type='text/html',charset='utf-8'");
require_once "fun.php";

Log::getInstance()->error(array('test'));die;

/*******php新建空对象*******/
//$object1 = new stdClass();
//$object2 = new class {};
//$object3 = (object)array();

//$image = "http://b.hiphotos.baidu.com/image/h%3D300/sign=c8a9d4e2841363270aedc433a18fa056/11385343fbf2b2114a65cd70c48065380cd78e41.jpg";
//$res = Curl::curlGet($image);
//file_put_contents('/www_tmp/a.png', $res);
//echo filesize('/www_tmp/a.png');

//$res = Curl::curlGet('http://mapglobal.baidu.com/mapsguide/hotcity?format=json&_=1517563094325');
//dd(json_encode($res));

//$image_binary = file_get_contents('/Users/wulinzhu/Documents/over.jpg');
//
//$im = imagecreatefromstring($image_binary);
//header('Content-Type: image/png');
//imagepng($im);
//imagedestroy($im);


//$res = file_get_contents('http://172.16.162.34:8090/');
//$res2 = Curl::curlGet('http://172.16.162.34:8090/');
//$res3 = Curl::request('http://172.16.162.34:8090');
//dd($res3);

//$oss = new Oss();
//$oss->upload($oss::BUCKET, 'test.png', file_get_contents('/Users/wulinzhu/Documents/gou.png'));
//$oss->download($oss::BUCKET, 'test.png', '/www_tmp/ttt.png');
//$oss->getFilePermit($oss::BUCKET, 'test.png');
//$oss->signUrl($oss::BUCKET, 'test.png');
//$oss->delete($oss::BUCKET, 'test.png');

//Log::getInstance()->debug(array('oss upload callback', json_encode($_REQUEST)));
//
//$url = 'http://localhost/wlz_phplib/oss/files.php';
//$ret = Curl::request($url, 'POST', array('file' => new \CURLFile('/www/wlz-project.tar.gz')));
//print_r($ret);


//$arr = array('name' => 'test');
//$object = (object)$arr;
//$curl = new Curl();
//$std = new stdClass();
//dd($std);


//ob_start();
//echo 111;
//ob_clean();
//echo 222;

$a = '9999999999999.990';
echo $a/10000;die;

dd(Fun::bcDivNumber($a));

echo openssl_encrypt('111', 'DES-ECB', '1');die;


$arr = array('name' => 'test', 'info' => array('a' => 'aaa', 'b' => (object)array('bbb', 'ccc' => (object)array('ccc'))));
$ret = Fun::objToArray($arr);
dd($ret);

$year = date('Y', time());
$last_year = date('Y', strtotime("-1 year"));

$ipc = new Ipc();
$ipc->set('key', '111');
$value = $ipc->get('key');
dd($value);die;

$arr = [111,[111]];
$ret = Fun::isArrayMultiDimension($arr);
var_dump($ret);die;

$xml = '';
$obj = simplexml_load_string($xml);
$xmljson= json_encode($objectxml );//将对象转换个JSON
$xmlarray=json_decode($xmljson,true);//将json转换成数组
//$arr = Fun::xmlToArray($xml);
dd($xmlarray);

$str = '1年';
$unit = substr($str, -1, 1);
$number = mb_substr($str, -2, 1);
$str = preg_split('//', $str, -1, PREG_SPLIT_NO_EMPTY);
$str = preg_split('/[0-9]/', $str);

$str = '';
$str = base64_decode($str);
dd($str);


//function test() {
//    try {
//        Log::getInstance()->info(array('try'));
//        return 111;
//    } catch (Exception $e) {
//        Log::getInstance()->debug(array('catch'));
//        return 222;
//    } finally {
//        Log::getInstance()->debug(array('finally'));
//        return 333;
//    }
//}
//
//echo test();