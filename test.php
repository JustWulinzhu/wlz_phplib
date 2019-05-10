<?php
/**
 * Created by PhpStorm.
 * Author: wulinzhu
 * Email: linzhu.wu@beebank.com
 * Date: 18/12/14 下午5:13
 */
require_once "fun.php";

/*******php新建空对象*******/
$object1 = new stdClass();
$object2 = new class {};
$object3 = (object)array();

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


$arr = array('name' => 'test');
$object = (object)$arr;
$curl = new Curl();
$std = new stdClass();
dd($std);









