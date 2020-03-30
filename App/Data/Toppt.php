<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 20/3/26 下午4:50
 * Email: 18515831680@163.com
 */

namespace App\Data;

use \S\Tools;

class Toppt {

    const FILE_CONTENT_TYPE_SINGLE = 'single';
    const FILE_CONTENT_TYPE_COMPLETE = 'complete';
    const FILE_CONTENT_TYPE_ARTICLE = 'article';

    private $chinese_num = [
        '一', '二', '三', '四', '五', '六', '七', '八', '九', '十',
    ];

    private $topic_type = [
        '单选', '多选', '判断',
    ];

    /**
     * txt、doc、docx生成ppt
     * @param $file
     * @param string $new_file_name
     * @return bool
     * @throws \S\Exceptions
     * @throws \Exception
     */
    public function transToPPT($type, $file, $new_file_name = '') {
        $ext_name = Tools::getExtendName($file);
        //读取文件内容
        if ($ext_name == 'txt') {
            $file_data = Tools::readFile($file);
        } else if ($ext_name == 'doc' || $ext_name == 'docx') {
            $file_data = (new \S\Office\Word())->read($file);
        } else {
            throw new \S\Exceptions('文件类型错误，只支持txt和word');
        }

        //区分文件内容类型选择、判断、讲义
        switch ($type) {
            case self::FILE_CONTENT_TYPE_SINGLE :
                $file_data = $this->selectMultiple($file_data);
                break;
            case self::FILE_CONTENT_TYPE_COMPLETE :
                $file_data = $this->completeMultiple($file_data);
                break;
            case self::FILE_CONTENT_TYPE_ARTICLE :
                throw new \S\Exceptions("暂未上线，敬请期待");
                break;
            default :
                throw new \S\Exceptions("file content type error");
        }

        return (new \S\Office\PowerPoint())->create($file_data, $new_file_name);
    }

    /**
     * 生成PPT前置操作，组装数据结构
     *
     * 处理选择题类型
     * 文件内容格式要求：序号要求是阿拉伯数字，不能出现中文大写数字，按照序号分割，一题一页ppt
     *
     * @param $file_data
     * @return array
     */
    public static function selectMultiple($file_data) {
        //按照层级关系拆分数据并组装数组
        $keys = self::getSliceKeyForSelect($file_data, Tools::getNum());
        //分段取出
        $data = self::getSliceData($file_data, $keys);
        foreach ($data as $key => $value) {
            $data[$key] = self::arrayToString($value);
        }

        return $data;
    }

    /**
     * 生成PPT前置操作，组装数据结构
     *
     * 处理整套题，含选择、多选、判断等
     * 文件内容格式要求：标题要写清选择、多选、判断类型，否则读取格式会错乱。每种类型下面序号采用阿拉伯数字，一题一页ppt
     *
     * @param $file_data
     * @return array
     */
    public function completeMultiple($file_data) {
        //按照层级关系拆分数据并组装数组
        $first_keys = self::getSliceKeyForComplete($file_data, $this->topic_type);
        //分段取出
        $first_data = self::getSliceData($file_data, $first_keys);

        //二维数据做相同的处理，按照层级关系拆分数据并组装数组，然后分段取出
        $second_datas = [];
        foreach ($first_data as $value) {
            $second_keys = self::getSliceKeyForSelect($value, Tools::getNum());
            $second_data = self::getSliceData($value, $second_keys);
            foreach ($second_data as $key => &$s) {
                $second_data[$key] = self::arrayToString($s);
            }

            $second_datas = array_merge($second_datas, $second_data); //转换成一维数组
        }

        return $second_datas;
    }

    /**
     * 按照层级关系拆分数据取得对应key for selectMultiple
     * @param $data
     * @param $rule
     * @return array
     */
    private static function getSliceKeyForSelect($data, $rule) {
        $keys = [];
        foreach ($data as $key => $value) {
            $num = Tools::findNum($value);
            if (! $num) continue; //没有数字的跳过

            if (in_array($num, $rule)) {
                $keys[] = $key;
            }
        }

        return $keys;
    }

    /**
     * 按照层级关系拆分数据取得对应key for completeMultiple
     * @param $data
     * @param $rules
     * @return array
     */
    private static function getSliceKeyForComplete($data, $rules) {
        $keys = [];
        foreach ($data as $key => $value) {
            $flag = false;
            foreach ($rules as $rule) {
                $flag = strstr($value, $rule);
                if ($flag) {
                    break;
                }
            }
            if ($flag) {
                $keys[] = $key;
            }
        }

        return $keys;
    }

    /**
     * 取得分段后的数据
     * @param $data
     * @param $keys
     * @return array
     */
    private static function getSliceData($data, $keys) {
        $ret = [];
        for ($i = 0; $i < count($keys); $i++) {
            if (! isset($keys[$i + 1])) { //防止超出数组长度
                $ret[] = array_slice($data, $keys[$i]);
            } else {
                $ret[] = array_slice($data, $keys[$i], $keys[$i + 1] - $keys[$i]);
            }
        }

        return $ret;
    }

    /**
     * 按照规则数组(一维)转字符串
     * @param $array
     * @return string
     */
    private static function arrayToString($array) {
        $str = '';
        foreach ($array as $item) {
            $str .= $item . "\n";
        }
        return $str;
    }

}