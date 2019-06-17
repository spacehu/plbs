<?php

namespace TigerDAL\Api;

use TigerDAL\BaseDAL;
use TigerDAL\Cms\CourseDAL as cmsCourseDAL;

class CourseDAL {

    /** 获取用户信息列表 */
    public static function getAll($currentPage, $pagesize, $keywords = '', $cat_id = '', $enterprise_id = '', $user_id = '') {
        $base = new BaseDAL();
        $limit_start = ($currentPage - 1) * $pagesize;
        $limit_end = $pagesize;
        $where = "";
        $join = '';
        if (!empty($keywords)) {
            $where .= " and c.name like '%" . $keywords . "%' ";
        }
        if ($cat_id !== '') {
            $where .= " and c.category_id = '" . $cat_id . "' ";
        }
        if ($enterprise_id !== '') {
            $where .= " and ec.enterprise_id = '" . $enterprise_id . "' ";
            $join = " left join " . $base->table_name("enterprise_course") . " as ec on c.id=ec.course_id ";
        }
        if ($user_id !== '') {
            $ids = self::getIdByUserCourse($user_id);
            if (!empty($ids)) {
                $where .= " and (ec.enterprise_id is null or c.id in (" . $ids . ") ) ";
                $join = " left join " . $base->table_name("enterprise_course") . " as ec on c.id=ec.course_id ";
            }
        }
        $sql = "select c.*,i.original_src,if(uc.status,uc.status,0) as ucStatus "
                . "from " . $base->table_name("course") . " as c "
                . $join
                . "left join " . $base->table_name("image") . " as i on i.id=c.media_id "
                . "left join " . $base->table_name("user_course") . " as uc on uc.course_id=c.id and uc.user_id=" . $user_id . " and uc.delete=0 "
                . "where c.`delete`=0 " . $where . " "
                . "order by c.order_by asc, c.edit_time desc "
                . "limit " . $limit_start . "," . $limit_end . " ;";
        return $base->getFetchAll($sql);
    }

    /** 获取数量 */
    public static function getTotal($keywords = '', $cat_id = '', $enterprise_id = '', $user_id = '') {
        $base = new BaseDAL();
        $where = "";
        $join = '';
        if (!empty($keywords)) {
            $where .= " and c.name like '%" . $keywords . "%' ";
        }
        if ($cat_id !== '') {
            $where .= " and c.category_id = '" . $cat_id . "' ";
        }
        if ($enterprise_id !== '') {
            $where .= " and ec.enterprise_id = '" . $enterprise_id . "' ";
            $join = " left join " . $base->table_name("enterprise_course") . " as ec on c.id=ec.course_id ";
        }
        if ($user_id !== '') {
            $ids = self::getIdByUserCourse($user_id);
            if (!empty($ids)) {
                $where .= " and (ec.enterprise_id is null or c.id in (" . $ids . ") ) ";
                $join = " left join " . $base->table_name("enterprise_course") . " as ec on c.id=ec.course_id ";
            }
        }
        $sql = "select count(1) as total from " . $base->table_name("course") . " as c "
                . $join
                . "where c.`delete`=0 " . $where . " limit 1 ;";
        return $base->getFetchRow($sql)['total'];
    }

    /** 获取用户信息 */
    public static function getOne($id, $user_id) {
        $base = new BaseDAL();
        $sql = "select c.*,i.original_src,count(l.id) as lessonCount,sum(ul.status) as lessonStartCount,uc.status as ucStatus "
                . "from " . $base->table_name("course") . " as c "
                . "left join " . $base->table_name("image") . " as i on i.id=c.media_id "
                . "left join " . $base->table_name("lesson") . " as l on l.course_id=c.id "
                . "left join " . $base->table_name("user_lesson") . " as ul on l.id=ul.lesson_id and ul.user_id=" . $user_id . " "
                . "left join " . $base->table_name("user_course") . " as uc on c.id=uc.course_id and uc.user_id=" . $user_id . " "
                . "where c.`delete`=0 and c.id=" . $id . "  limit 1 ;";
        return $base->getFetchRow($sql);
    }

    /** 获取用户信息 */
    public static function getByName($name) {
        $cms = new cmsCourseDAL();
        return $cms->getByName($name);
    }

    /** 参与课程 */
    public static function joinCourse($data) {
        $base = new BaseDAL();
        $sql = "select * from " . $base->table_name('user_course') . " where user_id=" . $data['user_id'] . " and course_id=" . $data['course_id'] . " ";
        if (!empty($base->getFetchRow($sql))) {
            return false;
        }
        return self::insertUserCourse($data);
    }

    /** 新建参与课程 */
    public static function insertUserCourse($data) {
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
            $sql = "insert into " . $base->table_name('user_course') . " values (null," . $set . ");";
            return $base->query($sql);
        } else {
            return true;
        }
    }

    /** 获取参与的课程 */
    public static function getIdByUserCourse($user_id) {
        $base = new BaseDAL();
        $sql = "select course_id "
                . "from " . $base->table_name("user_course") . " "
                . "where `delete`=0 and user_id=" . $user_id . " ;";
        $res = $base->getFetchAll($sql);
        if (!empty($res)) {
            foreach ($res as $v) {
                $_res[] = $v['course_id'];
            }
            return implode(',', $_res);
        }
        return false;
    }

}
