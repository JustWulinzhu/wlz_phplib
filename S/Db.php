<?php
/**
 * Created by PhpStorm.
 * Author: wulinzhu
 * Email: linzhu.wu@beebank.com
 * Date: 18/7/16 下午3:30
 * mysql操作类
 */

namespace S;

use Config\Conf;

class Db {

    private $mysql = null;
    private $table;

    /**
     * Db constructor.
     * @param $table
     * @throws \Exception
     */
    function __construct($table) {
        $conf = Conf::getConfig('db/db1');
        if (is_null($this->mysql)) {
            $this->mysql = new \PDO("mysql:host={$conf['host']}; dbname={$conf['db']}", $conf['user'], '');
        }
        $this->mysql->exec("SET names utf8");
        $this->table = $table;
    }

    /**
     * 开启事务
     * @return bool
     */
    public function transaction() { return $this->mysql->beginTransaction(); }

    /**
     * 提交事务
     * @return bool
     */
    public function commit() { return $this->mysql->commit(); }

    /**
     * 回滚事务
     * @return bool
     */
    public function rollBack() { return $this->mysql->rollBack(); }

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
     * @return array|bool
     * @throws \Exception
     */
    public function insert(array $data) {
        $arr = [];
        foreach ($data as $keys => $item) {
            $keys = '`' . $keys . '`';
            $arr[$keys] = $item;
        }
        $field = implode(",", array_keys($arr));
        $field_values = "'" . implode("','", $data) . "'";
        $sql = "insert into " . $this->table . ' (' . $field . ')' . " values (" . $field_values . ')';
        Log::getInstance()->debug(array($sql), 'sql');

        $res = $this->mysql->prepare($sql);
        $res->execute();
        return '00000' == $res->errorCode() ? true : $res->errorInfo();
    }

    /**
     * 通用update方法
     * @param array $data
     * @param array $condition
     * @return false|int
     * @throws \Exception
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
        Log::getInstance()->debug(array($sql), 'sql');

        return $this->mysql->exec($sql);
    }

    /**
     * 原生sql查询
     * @param $sql
     * @return array
     */
    public function query($sql) {
        $result = $this->mysql->query($sql, \PDO::FETCH_ASSOC);
        return self::formatQueryData($result);
    }

    /**
     * 查询一条记录
     * @param $condition
     * @param string $field
     * @return array|mixed
     * @throws \Exception
     */
    public function queryone($condition, $field = '*') {
        $ret = $this->select($condition, $field);
        if ($ret) {
            return current($ret);
        }
        return [];
    }

    /**
     * 查询一条记录
     * @param $sql
     * @return array|mixed
     */
    public function queryoneSql($sql) {
        $ret = $this->query($sql);
        if ($ret) {
            return current($ret);
        }
        return [];
    }

    /**
     * 通用sql查询
     * @param array $condition
     * @param string $field
     * @return array
     * @throws \Exception
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
        Log::getInstance()->debug(array($sql), 'sql');

        return $this->query($sql);
    }

}