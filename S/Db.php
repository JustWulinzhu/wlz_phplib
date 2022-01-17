<?php
/**
 * Created by PhpStorm.
 * Author: wulinzhu
 * Email: 18515831680@163.com
 * Date: 18/7/16 下午3:30
 * mysql操作类
 */

namespace S;

use Config\Conf;

class Db {

    const SUCCESS = '00000';

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
            $this->mysql = new \PDO("mysql:host={$conf['host']}; dbname={$conf['db']}", $conf['user'], $conf['pwd']);
            $this->mysql->exec("SET names utf8");
        }
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
     * 执行结果
     * @param $sql
     * @return array|false|int
     * @throws \Exception
     */
    private function exec($sql) {
        $ret = $this->mysql->exec($sql);
        $status = self::SUCCESS == $this->mysql->errorCode() ? $ret : $this->mysql->errorInfo();
        Log::getInstance()->debug([$sql, is_array($status) ? json_encode($status) : $status], 'sql');

        return $status;
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

        return $this->exec($sql);
    }

    /**
     * 通用update方法
     * @param array $data
     * @param array $condition
     * @return array|false|int
     * @throws \Exception
     */
    public function update(array $data, array $condition) {
        //修改字段
        $field_str = '';
        foreach (array_keys($data) as $key) {
            $field_str .= '`' . $key . '`' . '=' . "'" .$data[$key] . "'" . ',';
        }
        $field_str = trim($field_str, ',');
        //查询条件
        $condition_str = '';
        foreach ($condition as $key => $value) {
            $condition_str .= '`' . $key . '`' . '=' . "'" . $value . "'" . ' and ';
        }
        $condition_str = trim($condition_str, "and ");

        $sql = "update " . $this->table . ' set ' . $field_str . " where " . $condition_str;

        return $this->exec($sql);
    }

    /**
     * 通用删除方法
     * @param array $condition
     * @return array|false|int
     * @throws \Exception
     */
    public function delete(array $condition) {
        $value_str = '';
        foreach ($condition as $key => $value) {
            $value_str .= '`' . $key . '`' . '=' . "'" . $value . "'" . ' and ';
        }
        $value_str = trim($value_str, "and ");

        $sql = "delete from " . $this->table . " where " . $value_str;

        return $this->exec($sql);
    }

    /**
     * 通用查询方法
     * @param array $condition
     * @param string $field
     * @return array
     * @throws \Exception
     */
    public function select($condition = array(), $field = '*') {
        //查询字段
        if (is_array($field)) {
            $field = array_map(function ($e) { return '`' . $e . '`'; }, $field);
            $field_str = implode(",", $field);
        }
        $field_str = isset($field_str) ? $field_str : $field;

        if ($condition) {
            //查询条件
            $condition_str = '';
            foreach ($condition as $key => $value) {
                $condition_str .= '`' . $key . '`' . '=' . "'" . $value . "'" . ' and ';
            }
            $condition_str = substr($condition_str, 0, -5);

            $sql = "select " . $field_str . " from " . $this->table . " where " . $condition_str;
        } else {
            $sql = "select " . $field_str . " from " . $this->table;
        }

        return $this->query($sql);
    }

    /**
     * 原生sql查询
     * @param string $sql
     * @return array
     * @throws \Exception
     */
    public function query(string $sql) {
        Log::getInstance()->debug(array($sql), 'sql');
        $result = $this->mysql->query($sql, \PDO::FETCH_ASSOC);
        $result = self::SUCCESS == $this->mysql->errorCode() ? self::formatQueryData($result) : $this->mysql->errorInfo();

        return $result;
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
     * 查询一条记录sql版
     * @param $sql
     * @return array|mixed
     * @throws \Exception
     */
    public function queryoneSql($sql) {
        $ret = $this->query($sql);
        if ($ret) {
            return current($ret);
        }
        return [];
    }

    /**
     * 数据格式化
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
     * 大数据量分段查询，默认每次查10000条数据
     * @param $sql
     * @param int $limit
     * @return array
     * @throws \Exception
     */
    public function queryByLimit($sql, $limit = 10000) {
        $data = [];
        $start = 0;
        $flag = true;
        while ($flag) {
            $sql .= " limit {$start}, {$limit}";
            $ret = $this->query($sql);
            $data = array_merge($data, $ret);

            $start .= $limit;
            if (empty($ret) || count($ret) < $limit) {
                $flag = false;
            }
        }

        return $data;
    }

}