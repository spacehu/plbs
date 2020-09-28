<?php

namespace TigerDAL;

use http\Exception;
use mod\common;
use mod\init;
use mysqli;
use mysqli_result;
use TigerDAL\Api\LogDAL;
use TigerDAL\cli\LogDAL as cLogDAL;

/*
 * 基本数据类包
 * 类
 * 访问数据库用
 * 继承数据库包
 */

class BaseDAL
{

    //表名
    public $tab_name;
    //创建连接
    public $conn;
    private $sql;
    private $log;

    /**
     * 默认方法
     * @param string $_LOG
     */
    function __construct($_LOG = "DEBUG")
    {
        $this->tab_name = init::$config['mysql']['table_pre'];
        $this->mysqlStart();
        $this->log = $_LOG;
    }

    function __destruct()
    {
        if ($this->log == 'cli') {
            cLogDAL::save(date("Y-m-d H:i:s") . "-sql---" . json_encode($this->sql) . "", $this->log);
        } else {
            LogDAL::save(date("Y-m-d H:i:s") . "-sql---" . json_encode($this->sql) . "", $this->log);
        }
    }

    /** 创建mysql链接 */
    private function mysqlStart()
    {
        try {
            $conn = new mysqli(
                init::$config['mysql']['host'],
                init::$config['mysql']['user'],
                init::$config['mysql']['password'],
                init::$config['mysql']['dbName'],
                init::$config['mysql']['port']
            );
            $conn->query("set names utf8");
            $this->conn = $conn;
        } catch (Exception $ex) {
            var_dump($ex);
            exit;
        }
    }

    /** 获取列表
     * @param $sql
     * @return array|bool
     */
    public function getFetchAll($sql)
    {
        $result = $this->query($sql);
        if (!empty($result)) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        if (!isset($data)) {
            return false;
        } else {
            return $data;
        }
    }

    /** 获取单个
     * @param $sql
     * @return bool|array
     */
    public function getFetchRow($sql)
    {
        $result = $this->query($sql);
        if (!empty($result)) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        }
        if (!isset($data)) {
            return false;
        } else {
            return $data;
        }
    }

    /** 执行sql
     * @param $sql
     * @return mysqli_result|boolean For successful SELECT, SHOW, DESCRIBE or
     * EXPLAIN queries <b>mysqli_query</b> will return
     * a <b>mysqli_result</b> object.For other successful queries <b>mysqli_query</b> will
     * return true and false on failure.
     */
    public function query($sql)
    {
        $this->sql .= $sql;
        return $this->conn->query($sql);
    }

    /** 设置表名
     * @param $name
     * @return string
     */
    public function table_name($name)
    {
        return $this->tab_name . $name;
    }

    /**
     * 获取mysql最近一条的id
     */
    public function last_insert_id()
    {
        return $this->conn->insert_id;
    }

    /** 新增用户信息
     * @param $data
     * @param $_db
     * @return bool|mysqli_result
     */
    public function insert($data, $_db)
    {
        $match = ["NOW()","NULL"];
        if (is_array($data)) {
            $_data = [];
            foreach ($data as $v) {
                if (is_numeric($v)) {
                    $_data[] = " " . $v . " ";
                } else if (!isset($v)) {
                    $_data[] = " null ";
                } else if (in_array($v, $match)) {
                    $_data[] = " " . $v . " ";
                } else {
                    $_data[] = " '" . $v . "' ";
                }
            }
            $set = implode(',', $_data);
            $sql = "insert into " . $this->table_name($_db) . " values (null," . $set . ");";
            //Common::pr($sql);die;
            return $this->query($sql);
        } else {
            return true;
        }
    }

    /** 更新用户信息
     * @param $id
     * @param $data
     * @param $_db
     * @return bool|mysqli_result
     */
    public function update($id, $data, $_db)
    {
        $match = ["NOW()"];
        if (is_array($data)) {
            $_data = [];
            foreach ($data as $k => $v) {
                if (is_numeric($v)) {
                    $_data[] = " `" . $k . "`=" . $v . " ";
                } else if (!isset($v)) {
                    $_data[] = " `" . $k . "`= null ";
                } else if (in_array($v, $match)) {
                    $_data[] = " `" . $k . "`=" . $v . " ";
                } else {
                    $_data[] = " `" . $k . "`='" . $v . "' ";
                }
            }
            $set = implode(',', $_data);
            $sql = "update " . $this->table_name($_db) . " set " . $set . "  where id=" . $id . " ;";
            return $this->query($sql);
        } else {
            return true;
        }
    }

}
