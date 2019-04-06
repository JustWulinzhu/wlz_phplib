<?php
/**
 * Created by PhpStorm.
 * Author: wulinzhu
 * Email: linzhu.wu@beebank.com
 * Date: 18/7/16 下午3:30
 * mysql操作类
 */

class Mysql {

    private $mysql;
    private $table;

    function __construct($table) {
        $this->mysql = new PDO('mysql:host=106.12.2.39; dbname=redis', 'root', '');
        $this->mysql->exec("SET names utf8");
        $this->table = $table;
    }

    /**
     * @param $res
     * @return array
     */
    public static function formatQueryData($res){
        $data = [];
        if ($res) {
            foreach ($res as $r) {
                $data[] = $r;
            }
        }
        return $data;
    }

    /**
     * 通用插入方法
     * @param array $data
     * @return int
     */
    public function insert(array $data) {
        $field = implode(",", array_keys($data));
        $field_values = "'" . implode("','", $data) . "'";
        $sql = "insert into " . $this->table . ' (' . $field . ')' . " values (" . $field_values . ')';
        $res = $this->mysql->prepare($sql);
        $res->execute();
        return '00000' == $res->errorCode() ? true : $res->errorInfo();
    }

    /**
     * 通用update方法
     * @param $data
     * @param $condition
     * @return int
     */
    public function update(array $data, array $condition) {
        $field_str = '';
        foreach (array_keys($data) as $key) {
            $field_str .= $key . '=' . "'" .$data[$key] . "'" . ',';
        }
        $field_str = trim($field_str, ',');
        $value_str = '';
        foreach ($condition as $key => $value) {
            $value_str .= $key . '=' . "'" . $value . "'" . ' and ';
        }
        $value_str = trim($value_str, "and ");
        $sql = "update " . $this->table . ' set ' . $field_str . " where " . $value_str;
        return $this->mysql->exec($sql);
    }

    /**
     * 原生sql查询
     * @param $sql
     * @return array
     */
    public function query($sql) {
        $result = $this->mysql->query($sql, PDO::FETCH_ASSOC);
        return self::formatQueryData($result);
    }

    /**
     * 通用sql查询
     * @param $condition
     * @param string $field
     * @return array
     */
    public function select($condition = array(), $field = '*') {
        $condition_str = '';
        foreach ($condition as $key => $value) {
            $condition_str .= $key . '=' . "'" . $value . "'" . ' and ';
        }
        $condition_str = substr($condition_str, 0, -5);
        if (is_array($field)) {
            $field_str = implode(",", $field);
        }
        $field_str = isset($field_str) ? $field_str : $field;
        if ($condition) {
            $sql = "select " . $field_str . " from " . $this->table . " where " . $condition_str;
        } else {
            $sql = "select " . $field_str . " from " . $this->table;
        }
        return $this->query($sql);
    }

}