<?php

namespace TigerDAL\Api;

use TigerDAL\BaseDAL;
use TigerDAL\Cms\LessonDAL as cmsLessonDAL;

class LessonDAL {

    /** 获取用户信息列表 */
    public static function getAll($currentPage, $pagesize, $keywords = '', $course_id = '') {
        $base = new BaseDAL();
        $limit_start = ($currentPage - 1) * $pagesize;
        $limit_end = $pagesize;
        $where = "";
        if (!empty($keywords)) {
            $where .= " and name like '%" . $keywords . "%' ";
        }
        if ($course_id !== '') {
            $where .= " and c.course_id = '" . $course_id . "' ";
        }
        $sql = "select c.*,i.original_src from " . $base->table_name("lesson") . " as c "
                . "left join " . $base->table_name("image") . " as i on i.id=c.media_id "
                . "where c.`delete`=0 " . $where . " "
                . "order by c.edit_time desc "
                . "limit " . $limit_start . "," . $limit_end . " ;";
        return $base->getFetchAll($sql);
    }

    /** 获取数量 */
    public static function getTotal($keywords = '', $course_id = '') {
        $cms = new cmsLessonDAL();
        return $cms->getTotal($keywords, $course_id);
    }

    /** 获取用户信息 */
    public static function getOne($id) {
        $base = new BaseDAL();
        $sql = "select c.*,i.src from " . $base->table_name("lesson") . " as c "
                . "left join " . $base->table_name("media") . " as i on i.id=c.media_id "
                . "where c.`delete`=0 and c.id=" . $id . "  limit 1 ;";
        return $base->getFetchRow($sql);
    }

    /** 获取用户信息 */
    public static function getByName($name) {
        $cms = new cmsLessonDAL();
        return $cms->getByName($name);
    }

    /** 参与课时 */
    public static function joinLesson($data) {
        $base = new BaseDAL();
        $sql = "select * from " . $base->table_name('user_lesson') . " where user_id=" . $data['user_id'] . " and lesson_id=" . $data['lesson_id'] . " ";
        if (!empty($base->getFetchRow($sql))) {
            return false;
        }
        return self::insertUserLesson($data);
    }

    /** 新建参与课时 */
    public static function insertUserLesson($data) {
        $base = new BaseDAL();
        if (is_array($data)) {
            foreach ($data as $v) {
                if (is_numeric($v)) {
                    $_data[] = " " . $v . " ";
                } else {
                    $_data[] = " '" . $v . "' ";
                }
            }
            $set = implode(',', $_data);
            $sql = "insert into " . $base->table_name('user_lesson') . " values (null," . $set . ");";
            return $base->query($sql);
        } else {
            return true;
        }
    }

}
