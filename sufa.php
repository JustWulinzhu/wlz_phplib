<?php

require_once "fun.php";

    //冒泡排序
    $arr = [2, 5, 9, 1, 4];
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

    //选择排序
    $arr = [3, 5, 9, 1];
    $count = count($arr);
    for($i = 0; $i < $count - 1; $i++){
        $p = $i;
        for($j = $i + 1; $j < $count; $j++){
            if($arr[$p] > $arr[$j]){
                $p = $j;
            }
        }
        if($p != $i){
            $tmp = $arr[$p];
            $arr[$p] = $arr[$i];
            $arr[$i] = $tmp;
        }
    }

    //快速排序
    function quick_sort($arr)
    {
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
        $right = quick_sort($right);
        $left = quick_sort($left);
        $arr = array_merge($left, [$base_num], $right);
        return $arr;
    }

    //二分查找
    $arr = [1, 2 , 4, 5, 8, 9, 20, 22, 30];
    function zheban($arr, $num) {
        $count = count($arr);
        $midd = $count / 2 == 0 ? $count / 2 : ceil($count / 2);
        if ($num == $arr[$midd]) {
            return $arr[$midd];
        }
        $arr = array_slice($arr, $midd);
        $res = zheban($arr, $num);
        if ($res == $num) {
            return $res;
        } else {
            //zheban($arr, $num);
        }
    }