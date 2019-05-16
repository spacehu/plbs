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
        $and = "";
        if (!empty($keywords)) {
            $where .= " where ui.name like '%" . $keywords . "%' ";
            $and .= " and ui.name like '%" . $keywords . "%' ";
        }
        $sql = "select ui.* from " . $base->table_name("user_info") . " as ui " . $where . " order by ui.edit_time desc limit " . $limit_start . "," . $limit_end . " ;";
        if ($enterprise_id !== '') {
            $sql = "select ui.*,eu.status as euStatus "
                    . "from " . $base->table_name("user_info") . " as ui "
                    . "right join " . $base->table_name("enterprise_user") . " as eu on ui.id=eu.user_id and eu.enterprise_id=" . $enterprise_id . " "
                    . " where (eu.status=0 or eu.status=1)  " . $and . " "
                    . "order by ui.edit_time desc limit " . $limit_start . "," . $limit_end . " ;";
        }
        return $base->getFetchAll($sql);
    }

    /** 获取数量 */
    public static function getTotal($keywords, $enterprise_id = '') {
        $base = new BaseDAL();
        $where = "";
        $and = "";
        if (!empty($keywords)) {
            $where .= " where ui.name like '%" . $keywords . "%' ";
            $and .= " and ui.name like '%" . $keywords . "%' ";
        }
        $sql = "select count(1) as total from " . $base->table_name("user_info") . " " . $where . " limit 1 ;";
        if ($enterprise_id !== '') {
            $sql = "select count(1) as total "
                    . "from " . $base->table_name("user_info") . " as ui "
                    . "right join " . $base->table_name("enterprise_user") . " as eu on ui.id=eu.user_id and eu.enterprise_id=" . $enterprise_id . " "
                    . " where (eu.status=0 or eu.status=1)  " . $and . " ;";
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
                . "where uc.user_id=" . $user_id . " and c.enterprise_id=" . $enterprise_id . " and uc.`delete`=0 "
                . "order by uc.edit_time desc  ;";
        return $base->getFetchAll($sql);
    }

    /** 保存最新值  */
    public static function saveUserCourse($_data, $user_id, $_sourseData, $enterprise_id) {
        if (empty($_data)) {
            return true;
        }
        $base = new BaseDAL();
        // 获取已有
        $resCourse = self::getUserEnterpriseCourseList($user_id, $enterprise_id);
        if (!empty($resCourse)) {
            foreach ($resCourse as $v) {
                $_arr[] = $v['id'];
            }
            $_ucids = implode(',', $_arr);
            // 删除
            $sql = "update  " . $base->table_name('user_course') . " set `delete`=1 where `id` in (" . $_ucids . ");";
            $base->query($sql);
        }
        // 已有的开启
        $course_ids = implode(',', $_data);
        $sql = "update  " . $base->table_name('user_course') . " set `delete`=0 where `user_id`='" . $user_id . "' and course_id in (" . $course_ids . ") ;";
        $base->query($sql);

        // 插入
        $sql = "select course_id from " . $base->table_name('user_course') . " where `user_id`='" . $user_id . "' ;";
        $_hasCourse = $base->getFetchAll($sql);
        $_course_ids = $_data;
        if (!empty($_hasCourse)) {
            foreach ($_hasCourse as $v) {
                $_arr[] = $v['course_id'];
            }
            // 取 传入的课程id对比已有的课程id 找到并集（包括了新增的 和 公共课程） 然后取交集 也就是新增的集合
            $_course_ids = array_intersect($_data, array_diff($_data, $_arr));
        }
        foreach ($_course_ids as $v) {
            if ($v != 0) {
                $os = $_sourseData;
                array_unshift($os, $user_id, $v);
                //print_r($os);
                self::insertUserCourse($os);
            }
        }
        return true;
    }

    /** 新增用户课程信息 */
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
            //echo $sql;die;
            return $base->query($sql);
        } else {
            return true;
        }
    }

    public static function saveEnterpriseUser($user_id, $enterprise_id, $_data) {
        if (empty($_data)) {
            return true;
        }
        $base = new BaseDAL();
        // 删除
        $sql = "select id from " . $base->table_name('enterprise_user') . " where `user_id` = " . $user_id . " and enterprise_id=" . $enterprise_id . " ;";
        $row = $base->getFetchRow($sql);
        return self::updateEnterpriseUser($row['id'], $_data);
    }

    /** 更新用户信息 */
    public static function updateEnterpriseUser($id, $data) {
        $base = new BaseDAL();
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                if (is_numeric($v)) {
                    $_data[] = " `" . $k . "`=" . $v . " ";
                } else {
                    $_data[] = " `" . $k . "`='" . $v . "' ";
                }
            }
            $set = implode(',', $_data);
            $sql = "update " . $base->table_name('enterprise_user') . " set " . $set . "  where id=" . $id . " ;";
            //echo $sql;die;
            return $base->query($sql);
        } else {
            return true;
        }
    }

}
