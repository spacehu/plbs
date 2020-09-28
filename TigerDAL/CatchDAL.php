<?php

namespace TigerDAL;

/*
 * 基本数据类包
 * 类
 * 访问数据库用
 * 继承数据库包
 */

class CatchDAL {

    /** 记录异常 */
    public static function markError($name, $code, $detail) {
        // todo 添加日志进入数据库
        $res = self::saveSql($name, $code, $detail);
        // todo 添加日志进入log文件
        $res += self::saveFile($name, $code, $detail);
        return $res;
    }
    private static function saveSql($name, $code, $detail){
        $base = new BaseDAL();
        $sql = "insert into " . $base->table_name('error_log') . " values(null,'" . $name . "','" . $code . "','" . $detail . "',now()) ;";
        return $base->query($sql);
    }
    private static function saveFile($name, $code, $detail){
        return true;
    }

}
