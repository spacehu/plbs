<?php

namespace TigerDAL\Api;

use TigerDAL\BaseDAL;

class EnterpriseDAL {

    /** 获取用户信息 */
    public static function getByUserId($id) {
        $base = new BaseDAL();
        //$sql = "select * from " . $base->table_name("enterprise") . " where `delete`=0 and user_id='" . $id . "'  limit 1 ;";
        $sql = "select e.* "
                . "from " . $base->table_name("enterprise") . " as e "
                . "left join " . $base->table_name("user") . " as u on e.id=u.enterprise_id "
                . "where e.`delete`=0 and u.id='" . $id . "'  limit 1 ;";
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
        $sql = "select count(distinct(eu.user_id)) as num "
                . "from " . $base->table_name("enterprise_user") . " as eu "
                . "left join " . $base->table_name("user_course") . " as uc on uc.user_id = eu.user_id and uc.`delete`=0 "
                . "inner join " . $base->table_name("enterprise_course") . " as ec on uc.course_id = ec.course_id and ec.enterprise_id = " . $id . " and ec.`delete`=0 "
                . "where eu.`delete`=0 and eu.status=1 and eu.enterprise_id='" . $id . "'  limit 1 ;";
        return $base->getFetchRow($sql)['num'];
    }

    /** 获取企业员工的学习进度 */
    public static function getEnterpriseUserCourseExam($currentPage, $pagesize, $id) {
        $base = new BaseDAL();
        $limit_start = ($currentPage - 1) * $pagesize;
        $limit_end = $pagesize;
        $sql = "SELECT "
                . "u.id, "
                . "u.`NAME`, "
                . "u.photo, "
                . "count(DISTINCT(uc.id)) AS joinCourseCount, "
                . "count(DISTINCT(e.course_id)) AS passExamCount, "
                . "count(ul.id) AS userLessonTotal, "
                . "count(l.id) AS lessonCount, "
                . "if(count(l.id)<>0,count(ul.id)/count(l.id)*100,0) as progress "
                . "FROM " . $base->table_name("user_info") . " AS u  "
                . "LEFT JOIN " . $base->table_name("enterprise_user") . " AS eu ON u.id = eu.user_id AND eu.enterprise_id ='" . $id . "' "
                . "LEFT JOIN " . $base->table_name("user_course") . " AS uc ON uc.user_id = eu.user_id and uc.`delete`=0 "
                . "LEFT JOIN " . $base->table_name("enterprise_course") . " AS ec ON ec.enterprise_id = eu.enterprise_id and uc.course_id=ec.course_id "
                . "LEFT JOIN " . $base->table_name("exam") . " AS e ON e.course_id = uc.course_id and e.point>60 "
                . "LEFT JOIN " . $base->table_name("lesson") . " AS l ON l.course_id = uc.course_id  "
                . "LEFT JOIN " . $base->table_name("user_lesson") . " AS ul ON l.id = ul.lesson_id "
                . "WHERE eu.`delete` = 0 "
                . "AND eu.`STATUS` = 1 "
                . "group by u.id "
                . "limit " . $limit_start . "," . $limit_end . " ;";
        //echo $sql;die;
        return $base->getFetchAll($sql);
    }

    /** 获取企业员工的课程参与度 */
    public static function getEnterpriseUserCourseProgresses($currentPage, $pagesize, $id) {
        $base = new BaseDAL();
        $limit_start = ($currentPage - 1) * $pagesize;
        $limit_end = $pagesize;
        $sql = "SELECT "
                . "c.*,i.original_src,count(DISTINCT(uc.user_id)) as joinPerson "
                . "from " . $base->table_name("course") . "  as c  "
                . "left join " . $base->table_name("enterprise_course") . " as ec on c.id=ec.course_id "
                . "left join " . $base->table_name("image") . " as i on i.id=c.media_id "
                . "left join " . $base->table_name("enterprise_user") . " as eu on eu.enterprise_id = ec.enterprise_id and eu.`delete`=0 and eu.`status`=1 "
                . "left join " . $base->table_name("user_course") . " as uc on uc.course_id=c.id and eu.user_id = uc.user_id and uc.`delete`=0 "
                . "where ec.enterprise_id=" . $id . "  "
                . "and c.`delete`=0 "
                . "group by c.id "
                . "limit " . $limit_start . "," . $limit_end . " ;";
        $res = $base->getFetchAll($sql);
        $total = self::getEnterpriseUserCount($id);
        if (!empty($res)) {
            foreach ($res as $k => $v) {
                $_res[$k] = $v;
                if ($total > 0) {
                    $_res[$k]['progress'] = $v['joinPerson'] / $total * 100;
                } else {
                    $_res[$k]['progress'] = 0;
                }
            }
            return $_res;
        }
        return false;
    }

    /** 获取企业员工的考试合格率 */
    public static function getEnterpriseUserExamPass($currentPage, $pagesize, $id) {
        $base = new BaseDAL();
        $limit_start = ($currentPage - 1) * $pagesize;
        $limit_end = $pagesize;
        $sql = "SELECT "
                . "c.*,i.original_src,count(DISTINCT(e.user_id)) as passExam "
                . "from " . $base->table_name("course") . "  as c  "
                . "left join " . $base->table_name("enterprise_course") . " as ec on c.id=ec.course_id "
                . "left join " . $base->table_name("image") . " as i on i.id=c.media_id "
                . "left join " . $base->table_name("enterprise_user") . " as eu on ec.enterprise_id = eu.enterprise_id AND eu.`delete`=0 and eu.`status`=1 "
                . "left join " . $base->table_name("user_course") . " as uc on uc.course_id=c.id and eu.user_id = uc.user_id and uc.`delete`=0 "
                . "left join " . $base->table_name("exam") . " as e on uc.user_id=e.user_id and c.id=e.course_id and e.point>60 "
                . "where ec.enterprise_id=" . $id . "  "
                . "and c.`delete`=0 "
                . "group by c.id "
                . "limit " . $limit_start . "," . $limit_end . " ;";
        $res = $base->getFetchAll($sql);
        $total = self::getEnterpriseUserCount($id);
        if (!empty($res)) {
            foreach ($res as $k => $v) {
                $_res[$k] = $v;
                if ($total > 0) {
                    $_res[$k]['progress'] = $v['passExam'] / $total * 100;
                } else {
                    $_res[$k]['progress'] = 0;
                }
            }
            return $_res;
        }
        return false;
    }

}
