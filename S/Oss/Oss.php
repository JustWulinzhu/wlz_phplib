<?php
/**
 * Created by PhpStorm.
 * Author: wulinzhu
 * Email: linzhu.wu@beebank.com
 * Date: 19/4/4 下午5:35
 *
 * 阿里云oss核心类
 * oss帮助文档: https://help.aliyun.com/product/31815.html?spm=a2c4g.11186623.6.540.4f283554PXvZb1
 *
 * 默认bucket私有属性,只有授权access_key_id可以访问,需要通过 put和get来上传和获取文件
 *
 * @bucket
 *      阿里云oss bucket,采用默认bucket,可以在阿里云控制台新增
 * @name
 *      上传到oss上面的文件名称
 * @file
 *      要上传到oss的本地文件,绝对路径
 * @local_file
 *      下载到本地的文件名称,绝对路径
 *
 * demo
 * 上传 (new Oss())->uploadFile('bucket', 'uploaded_file_name', 'local_file_name');
 * 下载 (new Oss())->get('bucket', 'uploaded_file_name', 'local_file_name');
 *
 */

namespace S\Oss;

require_once dirname(dirname(__DIR__)) . '/Ext/aliyun-oss/autoload.php';

use S\Log;
use Config\Conf;
use OSS\OssClient;
use OSS\Core\OssException;

class Oss {

    const BUCKET = 'private-wulinzhu-test';

    private static $oss_client = null;
    private static $oss_conf = array();

    /**
     * 获取oss实例
     * @return OssClient|null
     * @throws OssException
     * @throws \Exception
     */
    private static function getOssInstance() {
        self::$oss_conf = Conf::getConfig('oss/oss');
        if (is_null(self::$oss_client)) {
            self::$oss_client = new OssClient(self::$oss_conf['access_key_id'], self::$oss_conf['access_key_secret'], self::$oss_conf['end_point']);
        }
        return self::$oss_client;
    }


    /**
     * 阿里云oss文件上传
     * @param $bucket
     * @param $name
     * @param $local_file
     * @param string $call_back_url
     * @return bool
     * @throws \Exception
     */
    public function uploadFile($bucket, $name, $local_file, $call_back_url = '') {
        try {
            $options = null;
            if ($call_back_url) {
                $call_back_host = parse_url($call_back_url)['host'];
                $params = "{
                    'callbackUrl':{$call_back_url},
                    'callbackHost':{$call_back_host},
                    'callbackBodyType':'application/json',
                }";
                $options = array(
                    OssClient::OSS_CALLBACK => $call_back_url,
                    OssClient::OSS_CALLBACK_VAR => $params
                );
            }
            Log::getInstance()->debug(array('oss uploadFile request params', json_encode(array($bucket, $name, $local_file, $options))));
            $ret = self::getOssInstance()->uploadFile($bucket, $name, $local_file, $options);
            Log::getInstance()->debug(array('oss uploadFile response', json_encode($ret)));
        } catch (OssException $e) {
            Log::getInstance()->error(array('oss uploadFile errors', $e->getCode(), $e->getMessage()));
            throw new \Exception($e->getCode(), $e->getMessage());
        }
        return true;
    }

    /**
     * 阿里云oss简单上传
     * @param $bucket
     * @param $name
     * @param $local_file
     * @return bool
     * @throws \Exception
     */
    public function put($bucket, $name, $local_file) {
        try {
            $ret = self::getOssInstance()->putObject($bucket, $name, $local_file);
            Log::getInstance()->debug(array('oss put response', json_encode($ret)));
        } catch (OssException $e) {
            Log::getInstance()->error(array('oss put errors', $e->getCode(), $e->getMessage()));
            throw new \Exception($e->getCode(), $e->getMessage());
        }
        return true;
    }

    /**
     * 阿里云oss分片上传
     * @param $bucket
     * @param string $name oss存储路径
     * @param string $local_file 本地文件绝对路径
     * @param int $part_size 分片文件大小，单位B
     * @return null
     * @throws OssException
     * @throws \Exception
     */
    public function partUpload($bucket, $local_file, $name, $part_size) {
        try {
            $upload_id = self::getOssInstance()->initiateMultipartUpload($bucket, $name);
            Log::getInstance()->debug(['upload_id', $upload_id]);
        } catch (OssException $e) {
            Log::getInstance()->debug(['init part upload error', $e->getMessage(), $e->getCode()]);
            throw new OssException($e->getMessage());
        }

        $file_size = filesize($local_file);
        $pieces = self::getOssInstance()->generateMultiuploadParts($file_size, $part_size);
        Log::getInstance()->debug(['pieces', count($pieces), json_encode($pieces)]);

        $response_upload_part = [];
        $upload_position = 0;
        $is_check_md5 = true;
        foreach ($pieces as $i => $piece) {
            $from_pos = $upload_position + (integer)$piece[OssClient::OSS_SEEK_TO];
            $to_pos = (integer)$piece[OssClient::OSS_LENGTH] + $from_pos - 1;
            $up_options = array(
                OssClient::OSS_FILE_UPLOAD  => $local_file,
                OssClient::OSS_PART_NUM     => ($i + 1),
                OssClient::OSS_SEEK_TO      => $from_pos,
                OssClient::OSS_LENGTH       => $to_pos - $from_pos + 1,
                OssClient::OSS_CHECK_MD5    => $is_check_md5,
            );
            if ($is_check_md5) { //MD5校验
                $content_md5 = \OSS\Core\OssUtil::getMd5SumForFile($local_file, $from_pos, $to_pos);
                $up_options[OssClient::OSS_CONTENT_MD5] = $content_md5;
            }
            try {
                //上传分片
                Log::getInstance()->debug(['upload part start', $i, $bucket, $name, $upload_id, json_encode($up_options)]);
                $upload_part_ret = self::getOssInstance()->uploadPart($bucket, $name, $upload_id, $up_options);
                Log::getInstance()->debug(['upload part ret', $i, $upload_part_ret]);
                $response_upload_part[] = $upload_part_ret;
            } catch(OssException $e) {
                Log::getInstance()->error(['upload part error', $e->getMessage(), $e->getCode()]);
                throw new OssException($e->getMessage());
            }
        }
        Log::getInstance()->debug(['response upload part', json_encode($response_upload_part)]);

        $upload_parts = array();
        foreach ($response_upload_part as $i => $e_tag) {
            $upload_parts[] = array(
                'PartNumber' => ($i + 1),
                'ETag' => $e_tag,
            );
        }

        try { //完成上传
            //在执行该操作时，需要提供所有有效的$uploadParts。OSS收到提交的$uploadParts后，会逐一验证每个分片的有效性
            //当所有的数据分片验证通过后，OSS将把这些分片组合成一个完整的文件
            $ret = self::getOssInstance()->completeMultipartUpload($bucket, $name, $upload_id, $upload_parts);
            Log::getInstance()->debug(['part upload ret', json_encode($ret)]);
        }  catch(OssException $e) {
            Log::getInstance()->error(['complete upload error', $e->getMessage(), $e->getCode()]);
            throw new OssException($e->getMessage());
        }

        return $ret;
    }

    /**
     * 阿里云oss下载
     * @param $bucket
     * @param $name
     * @param null $local_file
     * @return string
     * @throws \Exception
     */
    public function get($bucket, $name, $local_file = null) {
        if (! $this->isFileExist($bucket, $name)) {
            throw new \Exception('file does not exist');
        }
        try {
            $options = null;
            if ($local_file) { //$local_file,null下载到内存,如果是本地绝对路径即下载到对应路径
                $options[OssClient::OSS_FILE_DOWNLOAD] = $local_file;
            }
            $ret = self::getOssInstance()->getObject($bucket, $name, $options);
            Log::getInstance()->debug(array('oss get response', json_encode($ret)));
        } catch (OssException $e) {
            Log::getInstance()->error(array('oss get errors', $e->getCode(), $e->getMessage()));
            throw new \Exception($e->getCode(), $e->getMessage());
        }
        return $ret;
    }

    /**
     * 删除oss文件
     * @param $bucket
     * @param $name
     * @return bool
     * @throws \Exception
     */
    public function delete($bucket, $name) {
        try {
            $ret = self::getOssInstance()->deleteObject($bucket, $name);
            Log::getInstance()->debug(array('oss delete response', json_encode($ret)));
        } catch (OssException $e) {
            Log::getInstance()->error(array('oss delete errors', $e->getCode(), $e->getMessage()));
            throw new \Exception($e->getCode(), $e->getMessage());
        }
        return true;
    }

    /**
     * 判断文件是否存在oss
     * @param $bucket
     * @param $name
     * @return bool
     * @throws \Exception
     */
    public function isFileExist($bucket, $name) {
        try {
            $exist = self::getOssInstance()->doesObjectExist($bucket, $name);
        } catch (OssException $e) {
            Log::getInstance()->error(array('oss isFileExist errors', $e->getCode(), $e->getMessage()));
            throw new \Exception($e->getCode(), $e->getMessage());
        }
        return $exist;
    }

    /**
     * 获取oss文件权限
     * @param $bucket
     * @param $name
     * @return bool
     * @throws \Exception
     */
    public function getFilePermit($bucket, $name) {
        try {
            $permit = self::getOssInstance()->doesObjectExist($bucket, $name);
        } catch (OssException $e) {
            Log::getInstance()->error(array('oss getFilePermit errors', $e->getCode(), $e->getMessage()));
            throw new \Exception($e->getCode(), $e->getMessage());
        }
        return $permit;
    }

}