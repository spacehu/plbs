<?php

namespace TigerDAL\Cms;

use TigerDAL\BaseDAL;

class LessonImageDAL {

    /** 获取用户信息列表 */
    public static function getAll($aid) {
        $base = new BaseDAL();
        $sql = "select * from " . $base->table_name("lesson_image") . " where `lesson_id`='" . $aid . "' and `delete`=0  order by edit_time desc;";
        return $base->getFetchAll($sql);
    }

    /** 新增用户信息 */
    public static function insert($data) {
        $base = new BaseDAL();
        if (is_array($data)) {
            foreach ($data as $v) {
                $_data[] = " '" . $v . "' ";
            }
            $set = implode(',', $_data);
            $sql = "insert into " . $base->table_name('lesson_image') . " values (null," . $set . ");";
            //echo $sql;die;
            return $base->query($sql);
        } else {
            return true;
        }
    }

    /** 更新用户信息 */
    public static function update($id, $data) {
        $base = new BaseDAL();
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                $_data[] = " `" . $k . "`='" . $v . "' ";
            }
            $set = implode(',', $_data);
            $sql = "update " . $base->table_name('lesson_image') . " set " . $set . "  where id=" . $id . " ;";
            return $base->query($sql);
        } else {
            return true;
        }
    }

    /** 删除用户信息 */
    public static function delete($id) {
        $base = new BaseDAL();
        $sql = "update " . $base->table_name('lesson_image') . " set `delete`=1  where id=" . $id . " ;";
        return $base->query($sql);
    }

    /** 保存最新值 其他直接删除 */
    public static function save($_data, $aid, $_sourseData) {
        if (empty($_data)) {
            return true;
        }
        $base = new BaseDAL();
        $sql = "delete from " . $base->table_name('lesson_image') . " where `lesson_id`='" . $aid . "';";
        $base->query($sql);

        foreach ($_data as $v) {
            if ($v != 0) {
                $os = $_sourseData;
                array_unshift($os, $v, $aid);
                //print_r($os);
                self::insert($os);
            }
        }
        return true;
    }

    /**  */
    public static function getImageList($lesson_id) {
        $base = new BaseDAL();
        $sql = "select i.* from " . $base->table_name("lesson_image") . " as l "
                . "left join " . $base->table_name("image") . " as i on l.image_id=i.id "
                . "where l.`lesson_id`='" . $lesson_id . "' and l.`delete`=0  "
                . "order by l.edit_time desc;";
        return $base->getFetchAll($sql);
    }

}
