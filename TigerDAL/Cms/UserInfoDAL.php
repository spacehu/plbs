<?php

namespace TigerDAL\Cms;

use TigerDAL\BaseDAL;

class UserInfoDAL {

    /** 获取用户信息列表 */
    public static function getAll($currentPage, $pagesize, $keywords, $enterprise_id = '') {
        $base = new BaseDAL();
        $limit_start = ($currentPage - 1) * $pagesize;
        $limit_end = $pagesize;
        $where = "";
        if (!empty($keywords)) {
            $where .= " where ui.name like '%" . $keywords . "%' ";
        }
        $sql = "select ui.* from " . $base->table_name("user_info") . " as ui " . $where . " order by ui.edit_time desc limit " . $limit_start . "," . $limit_end . " ;";
        if ($enterprise_id !== '') {
            $sql = "select ui.* "
                    . "from " . $base->table_name("user_info") . " as ui "
                    . "right join " . $base->table_name("enterprise_user") . " as eu on ui.id=eu.user_id and eu.enterprise_id=" . $enterprise_id . " "
                    . " " . $where . " "
                    . "order by ui.edit_time desc limit " . $limit_start . "," . $limit_end . " ;";
        }
        return $base->getFetchAll($sql);
    }

    /** 获取数量 */
    public static function getTotal($keywords, $enterprise_id = '') {
        $base = new BaseDAL();
        $where = "";
        if (!empty($keywords)) {
            $where .= " where name like '%" . $keywords . "%' ";
        }
        $sql = "select count(1) as total from " . $base->table_name("user_info") . " " . $where . " limit 1 ;";
        if ($enterprise_id !== '') {
            $sql = "select count(1) as total "
                    . "from " . $base->table_name("user_info") . " as ui "
                    . "right join " . $base->table_name("enterprise_user") . " as eu on ui.id=eu.user_id and eu.enterprise_id=" . $enterprise_id . " "
                    . " " . $where . " ;";
        }
        return $base->getFetchRow($sql)['total'];
    }

    /** 获取用户信息 */
    public static function getOne($id) {
        $base = new BaseDAL();
        $sql = "select * from " . $base->table_name("user_info") . " where id=" . $id . "  limit 1 ;";
        return $base->getFetchRow($sql);
    }

    /** 获取用户信息 */
    public static function getByUserIdOne($id) {
        $base = new BaseDAL();
        $sql = "select * from " . $base->table_name("user_info") . " where user_id=" . $id . "  limit 1 ;";
        return $base->getFetchRow($sql);
    }

    /** 新增用户信息 */
    public static function insert($data) {
        $base = new BaseDAL();
        if (is_array($data)) {
            foreach ($data as $v) {
                $_data[] = " '" . $v . "' ";
            }
            $set = implode(',', $_data);
            $sql = "insert into " . $base->table_name('user_info') . " values (null," . $set . ");";
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
            $sql = "update " . $base->table_name('user_info') . " set " . $set . "  where id=" . $id . " ;";
            return $base->query($sql);
        } else {
            return true;
        }
    }

    /** 更新用户信息 */
    public static function updateByUserId($id, $data) {
        $base = new BaseDAL();
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                $_data[] = " `" . $k . "`='" . $v . "' ";
            }
            $set = implode(',', $_data);
            $sql = "update " . $base->table_name('user_info') . " set " . $set . "  where user_id=" . $id . " ;";
            return $base->query($sql);
        } else {
            return true;
        }
    }

    /** 获取用户企业课程 */
    public static function getUserEnterpriseCourseList($user_id, $enterprise_id) {
        $base = new BaseDAL();
        $sql = "select uc.* "
                . "from " . $base->table_name("user_course") . " as uc "
                . "left join " . $base->table_name("course") . " as c on uc.course_id=c.id "
                . "where uc.user_id=" . $user_id . " and c.enterprise_id=" . $enterprise_id . " "
                . "order by uc.edit_time desc  ;";
        return $base->getFetchAll($sql);
    }

    /** 保存最新值  */
    public static function saveUserCourse($_data, $aid, $_sourseData) {
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

}
