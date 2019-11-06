<?php

namespace S;

class Suanfa {

    /**
     * 冒泡排序
     * @param $arr
     * @return array
     */
    public static function bubble($arr) {
        $count = count($arr);
        for($i = 1; $i < $count; $i++){
            for($j = 0; $j < $count - $i; $j++){
                if($arr[$j + 1] < $arr[$j]){
                    $tmp = $arr[$j + 1];
                    $arr[$j + 1] = $arr[$j];
                    $arr[$j] = $tmp;
                }
            }
        }

        return $arr;
    }

    /**
     * 选择排序
     * @param $arr
     * @return array
     */
    public static function selection($arr) {
        $count = count($arr);
        for ($i = 0; $i < $count - 1; $i++) {
            $p = $i;
            for ($j = $i + 1; $j < $count; $j++) {
                if ($arr[$p] > $arr[$j]) {
                    $p = $j;
                }
            }
            if ($p != $i) {
                $tmp = $arr[$p];
                $arr[$p] = $arr[$i];
                $arr[$i] = $tmp;
            }
        }

        return $arr;
    }

    /**
     * 快速排序
     * @param $arr
     * @return array
     */
    public static function quick($arr) {
        $base_num = current($arr);
        $count = count($arr);
        $right = $left = [];
        for ($i = 0; $i < $count; $i++) {
            if ($arr[$i] > $base_num) {
                $right[] = $arr[$i];
            } else {
                $left[] = $arr[$i];
            }
        }
        $right = self::quick($right);
        $left = self::quick($left);
        $arr = array_merge($left, [$base_num], $right);

        return $arr;
    }

    /**
     * 二分查找(折半查找)
     * @param $arr
     * @param $num
     * @return array|mixed
     */
    public static function binarySearch($arr, $num) {
        $count = count($arr);
        $midd = $count / 2 == 0 ? $count / 2 : ceil($count / 2);
        if ($num == $arr[$midd]) {
            return $arr[$midd];
        }
        $arr = array_slice($arr, $midd);
        $res = self::binarySearch($arr, $num);
        if ($res == $num) {
            return $res;
        } else { //TODO::
            //self::binarySearch($arr, $num);
        }

        return $arr;
    }

}