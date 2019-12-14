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
            $where .= " and ui.name like '%" . $keywords . "%' ";
        }
        if ($enterprise_id !== '') {
            $where .= " and (eu.`status`=0 or eu.`status`=1) and eu.`delete`=0 and eu.enterprise_id=" . $enterprise_id . " ";
        }
        $sql = "select ui.*,eu.status as euStatus,ed.`name` as edName,ep.`name` as epName,e.`name` as eName "
                . " from " . $base->table_name("user_info") . " as ui "
                . " LEFT join " . $base->table_name("enterprise_user") . " as eu on ui.id=eu.user_id and (eu.`status`=0 or eu.`status`=1) and eu.`delete`=0 "
                . " LEFT join " . $base->table_name("enterprise") . " as e on e.id=eu.enterprise_id and e.`delete`=0 "
                . " LEFT join " . $base->table_name("enterprise_department") . " as ed on eu.department_id=ed.id and ed.`delete`=0 "
                . " LEFT join " . $base->table_name("enterprise_position") . " as ep on eu.position_id=ep.id and ep.`delete`=0 "
                . " where ui.id >0 " . $where . " "
                . " order by ui.edit_time desc limit " . $limit_start . "," . $limit_end . " ;";
        //echo $sql;
        return $base->getFetchAll($sql);
    }

    /** 获取数量 */
    public static function getTotal($keywords, $enterprise_id = '') {
        $base = new BaseDAL();
        $where = "";
        if (!empty($keywords)) {
            $where .= " and ui.name like '%" . $keywords . "%' ";
        }
        if ($enterprise_id !== '') {
            $where .= " and (eu.`status`=0 or eu.`status`=1) and eu.`delete`=0 and eu.enterprise_id=" . $enterprise_id . " ";
        }
        $sql = "select count(1) as total "
                . " from " . $base->table_name("user_info") . " as ui "
                . " LEFT join " . $base->table_name("enterprise_user") . " as eu on ui.id=eu.user_id and (eu.`status`=0 or eu.`status`=1) and eu.`delete`=0 "
                . " where ui.id >0 " . $where . "  ;";
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
                . "left join " . $base->table_name("enterprise_course") . " as ec on c.id=ec.course_id "
                . "where uc.user_id=" . $user_id . " and ec.enterprise_id=" . $enterprise_id . " and uc.`delete`=0 "
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

    public static function saveEnterpriseUser($user_ids, $enterprise_id, $_data) {
        if (empty($_data)) {
            return true;
        }
        if ($_data['status'] == 2) {
            $_data['department_id'] = 0;
            $_data['position_id'] = 0;
        }
        $base = new BaseDAL();
        // 删除
        $sql = "select id from " . $base->table_name('enterprise_user') . " where `delete`=0 and `user_id` in (" . $user_ids . ") and enterprise_id=" . $enterprise_id . " ;";
        $row = $base->getFetchRow($sql);
        return self::updateEnterpriseUser($row['id'], $_data);
    }

    /** 企业强绑定用户 */
    public static function saveEnterpriseUserByPhone($phone, $enterprise_id) {
        $base = new BaseDAL();
        $sql = "select * from " . $base->table_name('user_info') . " where phone='" . $phone . "' ;";
        $_userinfo = $base->getFetchRow($sql);
        if (empty($_userinfo)) {
            return false;
        }
        // 删除
        self::deleteEnterpriseUser($_userinfo['id'], $enterprise_id);
        $sql = "select id from " . $base->table_name('enterprise_user') . " where `user_id` = " . $_userinfo['id'] . " and enterprise_id=" . $enterprise_id . " ;";
        $row = $base->getFetchRow($sql);
        if (empty($row)) {
            $_data = [
                'enterprise_id' => $enterprise_id,
                'user_id' => $_userinfo['id'],
                'status' => '1',
                'add_by' => \mod\common::getSession("id"),
                'add_time' => date("Y-m-d H:i:s"),
                'edit_by' => \mod\common::getSession("id"),
                'edit_time' => date("Y-m-d H:i:s"),
                'delete' => '0',
                'department_id'=>'0',
                'position_id'=>'0',
            ];
            return self::insertEnterpriseUser($_data);
        } else if ($row['delete'] == 1) {
            $_data = [
                'status' => '1',
                'edit_by' => \mod\common::getSession("id"),
                'delete' => '0',
            ];
            return self::updateEnterpriseUser($row['id'], $_data);
        }
        return true;
    }

    /** 新增用户企业关系信息 */
    public static function insertEnterpriseUser($data) {
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
            $sql = "insert into " . $base->table_name('enterprise_user') . " values (null," . $set . ");";
            //echo $sql;die;
            return $base->query($sql);
        } else {
            return true;
        }
    }

    /** 更新用户企业关系信息 */
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

    /** 删除用户企业关系信息 */
    public static function deleteEnterpriseUser($user_id, $enterprise_id) {
        $base = new BaseDAL();
        $sql = "update " . $base->table_name('enterprise_user') . " set `delete`=1  where user_id=" . $user_id . " and enterprise_id<>" . $enterprise_id . " ;";
        return $base->query($sql);
    }

    /** 获取 企业 职级关系 的用户列表 */
    public static function getEnterpriseUser($enterprise_id, $department_id = '', $position_id = '') {
        $base = new BaseDAL();
        $where = "";
        if (isset($department_id) && is_numeric($department_id)) {
            $where = " and (eu.department_id = " . $department_id . " or eu.department_id = 0  or eu.department_id is null or ( ed.`delete`=1 ) ) ";

            if (isset($position_id) && is_numeric($position_id)) {
                $where = " and (eu.department_id = " . $department_id . " )  "
                        . " and (eu.position_id = " . $position_id . " or eu.position_id =0 or eu.position_id is null or ( ep.`delete`=1 )) ";
            }
        }
        $sql = "select ui.*,eu.status as euStatus,eu.department_id,eu.position_id,ed.`delete` as edDelete,ep.`delete` as epDelete "
                . "from " . $base->table_name("user_info") . " as ui "
                . "left join " . $base->table_name("enterprise_user") . " as eu on ui.id=eu.user_id "
                . "left join " . $base->table_name("enterprise") . " as e on e.id=eu.enterprise_id "
                . "left join " . $base->table_name("enterprise_department") . " as ed ON ed.id = eu.department_id "
                . "left join " . $base->table_name("enterprise_position") . " as ep ON ep.id = eu.position_id "
                . " where (eu.status=1) and eu.`delete`=0 and e.`delete`=0 and eu.enterprise_id=" . $enterprise_id . "  " . $where . " "
                . "order by ui.edit_time desc ;";
        //echo $sql;
        return $base->getFetchAll($sql);
    }

}
