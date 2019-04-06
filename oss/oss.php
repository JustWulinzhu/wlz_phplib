<?php
/**
 * Created by PhpStorm.
 * Author: wulinzhu
 * Email: linzhu.wu@beebank.com
 * Date: 19/4/4 下午5:35
 *
 * 阿里云oss文件系统类
 * oss帮助文档: https://help.aliyun.com/product/31815.html?spm=a2c4g.11186623.6.540.4f283554PXvZb1
 *
 * 默认bucket私有属性,只有授权access_key_id可以访问,需要通过 put和get来上传和获取文件
 *
 * $bucket 阿里云oss bucket,采用默认bucket,可以在阿里云控制台新增
 * $name 上传到oss上面的文件名称
 * $file 要上传到oss的本地文件,绝对路径
 * $local_file 下载到本地的文件名称,绝对路径
 *
 * demo
 *
 * 上传 (new Oss())->uploadFile('bucket', 'uploaded_file_name', 'local_file_name');
 * 下载 (new Oss())->get('bucket', 'uploaded_file_name', 'local_file_name');
 */

require_once dirname(__DIR__) . "/fun.php";
require_once dirname(__DIR__) . '/aliyun-oss/autoload.php';

use OSS\OssClient;
use OSS\Core\OssException;

class Oss {

    const BUCKET = 'private-wulinzhu-test';

    const ACCESS_KEY_ID = 'LTAI7zgKaWGUoXg6';
    const ACCESS_KEY_SECRET = 'bY8sOCy5Pm2lC1ARNOPcB9cqZOdyyo';
    const END_POINT = 'oss-cn-beijing.aliyuncs.com';

    private static $oss_client = null;

    /**
     * 获取oss实例
     * @return null|OssClient
     */
    private static function getOssInstance() {
        if (is_null(self::$oss_client)) {
            self::$oss_client = new OssClient(self::ACCESS_KEY_ID, self::ACCESS_KEY_SECRET, self::END_POINT);
        }
        return self::$oss_client;
    }


    /**
     * 阿里云oss文件上传
     * @param $bucket
     * @param $name
     * @param $local_file
     * @return bool
     * @throws Exception
     */
    public function uploadFile($bucket, $name, $local_file) {
        try {
            $ret = self::getOssInstance()->uploadFile($bucket, $name, $local_file);
            Log::getInstance()->debug(array('oss uploadFile response', json_encode($ret)));
        } catch (OssException $e) {
            Log::getInstance()->error(array('oss uploadFile errors', $e->getCode(), $e->getMessage()));
            throw new Exception($e->getCode(), $e->getMessage());
        }
        return true;
    }

    /**
     * 阿里云oss简单上传
     * @param $bucket
     * @param $name
     * @param $local_file
     * @return bool
     * @throws Exception
     */
    public function put($bucket, $name, $local_file) {
        try {
            $ret = self::getOssInstance()->putObject($bucket, $name, $local_file);
            Log::getInstance()->debug(array('oss put response', json_encode($ret)));
        } catch (OssException $e) {
            Log::getInstance()->error(array('oss put errors', $e->getCode(), $e->getMessage()));
            throw new Exception($e->getCode(), $e->getMessage());
        }
        return true;
    }

    /**
     * 阿里云oss下载
     * @param $bucket
     * @param $name
     * @param null $local_file
     * @return bool
     * @throws Exception
     */
    public function get($bucket, $name, $local_file = null) {
        if (! $this->isFileExist($bucket, $name)) {
            throw new Exception('file does not exist');
        }
        try {
            //$local_file,null下载到内存,如果是本地绝对路径即下载到对应路径
            $options = is_null($local_file) ? null : array(OssClient::OSS_FILE_DOWNLOAD => $local_file);
            $ret = self::getOssInstance()->getObject($bucket, $name, $options);
            Log::getInstance()->debug(array('oss get response', json_encode($ret)));
        } catch (OssException $e) {
            Log::getInstance()->error(array('oss get errors', $e->getCode(), $e->getMessage()));
            throw new Exception($e->getCode(), $e->getMessage());
        }
        return true;
    }

    /**
     * 删除oss文件
     * @param $bucket
     * @param $name
     * @return bool
     * @throws Exception
     */
    public function delete($bucket, $name) {
        try{
            $ret = self::getOssInstance()->deleteObject($bucket, $name);
            Log::getInstance()->debug(array('oss delete response', json_encode($ret)));
        } catch (OssException $e) {
            Log::getInstance()->error(array('oss delete errors', $e->getCode(), $e->getMessage()));
            throw new Exception($e->getCode(), $e->getMessage());
        }
        return true;
    }

    /**
     * 判断文件是否存在oss
     * @param $bucket
     * @param $name
     * @return bool
     * @throws Exception
     */
    public function isFileExist($bucket, $name) {
        try{
            $exist = self::getOssInstance()->doesObjectExist($bucket, $name);
        } catch (OssException $e) {
            Log::getInstance()->error(array('oss isFileExist errors', $e->getCode(), $e->getMessage()));
            throw new Exception($e->getCode(), $e->getMessage());
        }
        return $exist;
    }

    /**
     * 获取oss文件权限
     * @param $bucket
     * @param $name
     * @return bool
     * @throws Exception
     */
    public function getFilePermit($bucket, $name) {
        try {
            $permit = self::getOssInstance()->doesObjectExist($bucket, $name);
        } catch (OssException $e) {
            Log::getInstance()->error(array('oss getFilePermit errors', $e->getCode(), $e->getMessage()));
            throw new Exception($e->getCode(), $e->getMessage());
        }
        return $permit;
    }

}