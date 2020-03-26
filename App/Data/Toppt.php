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

    private $chinese_num = [
        '一', '二', '三', '四', '五', '六', '七', '八', '九', '十',
    ];

    /**
     * txt、doc、docx生成ppt
     * @param $file
     * @param string $new_file_name
     * @return bool
     * @throws \S\Exceptions
     * @throws \Exception
     */
    public function transToPPT($file, $new_file_name = '') {
        $ext_name = Tools::getExtendName($file);
        //读取文件内容
        if ($ext_name == 'txt') {
            $file_data = Tools::readFile($file);
        } else if ($ext_name == 'doc' || $ext_name == 'docx') {
            $file_data = (new \S\Office\Word())->read($file);
        } else {
            throw new \S\Exceptions('文件类型错误，只支持txt和word');
        }

        //TODO::区分文件内容类型 选择 判断 讲义
        $file_data = $this->selectMultiple($file_data);
        return (new \S\Office\PowerPoint())->create($file_data, $new_file_name);
    }

    /**
     * 生成PPT前置操作，组装数据结构
     *
     * 处理选择题类型
     * 文件内容格式要求：序号要求是阿拉伯数字，不能出现中文大写数字，按照序号分割，一题一页
     *
     * @param $file_data
     * @return array
     */
    public static function selectMultiple($file_data) {
        //按照层级关系拆分数据并组装数组
        $keys = self::getSliceKey($file_data, Tools::getNum());
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
     * 文件内容格式要求：中文大写数字区分选择、多选、判断，每种类型下面序号采用阿拉伯数字，一题一页
     *
     * @param $file_data
     * @return array
     */
    public function completeMultiple($file_data) {
        //按照层级关系拆分数据并组装数组
        $first_keys = self::getSliceKey($file_data, $this->chinese_num);
        //分段取出
        $first_data = self::getSliceData($file_data, $first_keys);

        //二维数据做相同的处理，按照层级关系拆分数据并组装数组，然后分段取出
        $second_datas = [];
        foreach ($first_data as $value) {
            $second_keys = self::getSliceKey($value, Tools::getNum());
            $second_data = self::getSliceData($value, $second_keys);
            foreach ($second_data as $key => &$s) {
                $second_data[$key] = self::arrayToString($s);
            }

            $second_datas[] = $second_data;
        }

        return $second_datas;
    }

    /**
     * 按照层级关系拆分数据取得对应key
     * @param $data
     * @param $rule
     * @return array
     */
    private static function getSliceKey($data, $rule) {
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