<?php

namespace TigerDAL\Api;

use TigerDAL\BaseDAL;

class EnterpriseDAL {

    /** 获取用户信息 */
    public static function getByUserId($id) {
        $base = new BaseDAL();
        $sql = "select * from " . $base->table_name("enterprise") . " where `delete`=0 and user_id='" . $id . "'  limit 1 ;";
        return $base->getFetchRow($sql);
    }

    /** 获取企业员工数 */
    public static function getEnterpriseUserCount($id) {
        $base = new BaseDAL();
        $sql = "select count(id) as num from " . $base->table_name("enterprise_user") . " where `delete`=0 and `status`=1 and enterprise_id='" . $id . "'  limit 1 ;";
        //echo $sql;
        return $base->getFetchRow($sql)['num'];
    }

    /** 获取参与企业课程的企业员工数 */
    public static function getJoinCourseUserCount($id) {
        $base = new BaseDAL();
        $sql = "select count(eu.id) as num "
                . "from " . $base->table_name("enterprise_user") . " as eu "
                . "left join " . $base->table_name("user_course") . " as uc on uc.user_id = eu.user_id "
                . "inner join " . $base->table_name("course") . " as c on uc.course_id = c.id and c.enterprise_id = " . $id . " "
                . "where eu.`delete`=0 and eu.status=1 and eu.enterprise_id='" . $id . "'  limit 1 ;";
        return $base->getFetchRow($sql)['num'];
    }

    /** 获取企业员工的学习进度 */
    public static function getEnterpriseUserCourseExam($currentPage, $pagesize, $id) {
        $base = new BaseDAL();
        $limit_start = ($currentPage - 1) * $pagesize;
        $limit_end = $pagesize;
        $sql = "SELECT "
                . "u.id, u.`NAME`, u.photo, count(DISTINCT(uc.id)) AS joinCourseCount, count(DISTINCT(e.course_id)) AS passExamCount, count(ul.id) AS courseTotal, count(l.id) AS userCourseCount "
                . "FROM " . $base->table_name("user_info") . " AS u  "
                . "LEFT JOIN " . $base->table_name("enterprise_user") . " AS eu ON u.id = eu.user_id AND eu.enterprise_id ='" . $id . "' "
                . "LEFT JOIN " . $base->table_name("user_course") . " AS uc ON uc.user_id = eu.user_id "
                . "inner JOIN " . $base->table_name("course") . " AS c ON c.enterprise_id = eu.enterprise_id and uc.course_id=c.id "
                . "LEFT JOIN " . $base->table_name("exam") . " AS e ON e.course_id = uc.course_id and e.point>60 "
                . "LEFT JOIN " . $base->table_name("lesson") . " AS l ON l.course_id = uc.course_id  "
                . "LEFT JOIN " . $base->table_name("user_lesson") . " AS ul ON l.id = ul.lesson_id "
                . "WHERE eu.`delete` = 0 "
                . "AND eu. STATUS = 1 "
                . "and uc.`delete`=0 "
                . "limit " . $limit_start . "," . $limit_end . " ;";
        return $base->getFetchAll($sql);
    }

    /** 获取企业员工的课程参与度 */
    public static function getEnterpriseUserCourseProgresses($currentPage, $pagesize, $id) {
        $base = new BaseDAL();
        $limit_start = ($currentPage - 1) * $pagesize;
        $limit_end = $pagesize;
        $sql = "SELECT "
                . "u.id, u.`NAME`, u.photo, count(DISTINCT(uc.id)) AS joinCourseCount, count(DISTINCT(e.course_id)) AS passExamCount, count(ul.id) AS courseTotal, count(l.id) AS userCourseCount "
                . "FROM " . $base->table_name("user_info") . " AS u  "
                . "LEFT JOIN " . $base->table_name("enterprise_user") . " AS eu ON u.id = eu.user_id AND eu.enterprise_id ='" . $id . "' "
                . "LEFT JOIN " . $base->table_name("user_course") . " AS uc ON uc.user_id = eu.user_id "
                . "inner JOIN " . $base->table_name("course") . " AS c ON c.enterprise_id = eu.enterprise_id and uc.course_id=c.id "
                . "LEFT JOIN " . $base->table_name("exam") . " AS e ON e.course_id = uc.course_id and e.point>60 "
                . "LEFT JOIN " . $base->table_name("lesson") . " AS l ON l.course_id = uc.course_id  "
                . "LEFT JOIN " . $base->table_name("user_lesson") . " AS ul ON l.id = ul.lesson_id "
                . "WHERE eu.`delete` = 0 "
                . "AND eu. STATUS = 1 "
                . "limit " . $limit_start . "," . $limit_end . " ;";
        return $base->getFetchAll($sql);
    }

}
