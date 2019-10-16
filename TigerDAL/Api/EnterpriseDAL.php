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
    public static function getEnterpriseUserCount($id, $keywords="", $_startTime="", $_endTime="") {
        $base = new BaseDAL();
        $and = "";
        if (!empty($keywords)) {
            $and .= " and name like '%" . $keywords . "%' ";
        }
        if (!empty($_startTime)) {
            $and .= " and last_login_time >= '" . $_startTime . "' ";
        }
        if (!empty($_endTime)) {
            $and .= " and last_login_time <= '" . $_endTime . "' ";
        }
        $sql = "select count(id) as num from " . $base->table_name("enterprise_user") . " where `delete`=0 and `status`=1 and enterprise_id='" . $id . "'  ".$and." limit 1 ;";
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
    public static function getEnterpriseUserCourseExam($currentPage, $pagesize, $id, $keywords="", $_startTime="", $_endTime="") {
        $base = new BaseDAL();
        $limit_start = ($currentPage - 1) * $pagesize;
        $limit_end = $pagesize;
        $and = "";
        if (!empty($keywords)) {
            $and .= " and u.name like '%" . $keywords . "%' ";
        }
        if (!empty($_startTime)) {
            $and .= " and u.last_login_time >= '" . $_startTime . "' ";
        }
        if (!empty($_endTime)) {
            $and .= " and u.last_login_time <= '" . $_endTime . "' ";
        }
        $_sql="select u.id,u.`name` as `NAME`,u.photo,u.last_login_time from " . $base->table_name("user_info") . " AS u    
                LEFT JOIN " . $base->table_name("enterprise_user") . " AS eu ON u.id = eu.user_id
                where eu.enterprise_id= ".$id." and eu.`delete`=0 and eu.`status`=1 ".$and."
                limit " . $limit_start . "," . $limit_end . " ;";
        $_res=$base->getFetchAll($_sql);
        $result=[];
        $_arr=[];
        if(!empty($_res)){
            foreach($_res as $k=>$v){
                $result[]=$v;
                $_arr[]=$v['id'];
            }
            $_udis=implode(',',$_arr);
            $sql="SELECT 
                    euce.id,
                    euce.uname as `NAME`,
                    euce.photo,
                    COUNT(DISTINCT (euce.course_id)) AS joinCourseCount,
                    COUNT(DISTINCT (euce.eid)) AS passExamCount,
                    COUNT( (euce.ulid)) AS userLessonTotal,
                    COUNT( (euce.lid)) AS lessonCount,
                    IF(COUNT(euce.lid) <> 0,
                        COUNT(euce.ulid) / COUNT(euce.lid) * 100,
                        0) AS progress
                        from 
                (
                SELECT 
                    u.id,
                    u.`NAME` as uname,
                    u.photo,
                    uc.course_id,
                    eu.department_id as eudid,
                    eu.position_id as eupid,
                    ec.department_id as ecdid,
                    ec.position_id as ecpid,
                    ed.`delete` as edd,
                    ep.`delete` as epd,
                    e.id as eid,e.`point` as epoint,
                    c.percentage,
                    l.id as lid,
                    ul.id as ulid
                
                FROM
                " . $base->table_name("user_info") . " AS u    
                    LEFT JOIN " . $base->table_name("enterprise_user") . " AS eu ON u.id = eu.user_id
                        LEFT JOIN " . $base->table_name("user_course") . " AS uc ON uc.user_id = eu.user_id AND uc.`delete` = 0 
                        left join " . $base->table_name("course") . " as c on uc.course_id=c.id and c.`delete` =0 
                        left join " . $base->table_name("enterprise_course") . " as ec on ec.course_id=uc.course_id and ec.enterprise_id=eu.enterprise_id and ec.`delete`=0 
                        LEFT JOIN " . $base->table_name("enterprise_department") . " AS ed ON ed.id = eu.department_id 
                        LEFT JOIN " . $base->table_name("enterprise_position") . " AS ep ON ep.id = eu.position_id 
                        
                        LEFT JOIN " . $base->table_name("exam") . " AS e ON e.course_id = uc.course_id and e.user_id = u.id AND e.`point` > c.percentage
                        LEFT JOIN " . $base->table_name("lesson") . " AS l ON l.course_id = uc.course_id and l.`delete`=0
                        LEFT JOIN " . $base->table_name("user_lesson") . " AS ul ON l.id = ul.lesson_id and ul.`delete`=0 and ul.user_id=u.id
                WHERE
                    eu.`delete` = 0 AND eu.`STATUS` = 1
                        and u.id in (".$_udis.")
                        and (ec.department_id is null  or (ec.department_id is not null and ed.delete = 0))
                        and (ec.position_id is null  or (ec.position_id is not null and ep.delete = 0))
                order by u.id asc
                ) as euce
                group by euce.id
                order by euce.id asc;";
            //echo $sql;die;
            // AND eu.enterprise_id = '".$id."'
            $res = $base->getFetchAll($sql);
            $resB=[];
            if(!empty($res)){
                foreach($res as $k=>$v){
                    $resB[$v['id']]=$v;
                }
            }
            foreach($result as $k=>$v){
                $result[$k]['joinCourseCount']=!empty($resB[$v['id']])?$resB[$v['id']]['joinCourseCount']:'0';
                $result[$k]['passExamCount']=!empty($resB[$v['id']])?$resB[$v['id']]['passExamCount']:'0';
                $result[$k]['userLessonTotal']=!empty($resB[$v['id']])?$resB[$v['id']]['userLessonTotal']:'0';
                $result[$k]['lessonCount']=!empty($resB[$v['id']])?$resB[$v['id']]['lessonCount']:'0';
                $result[$k]['progress']=!empty($resB[$v['id']])?$resB[$v['id']]['progress']:'0';
            }
        }
        return $result;
    }

    /** 获取企业员工的课程参与度 */
    public static function getEnterpriseUserCourseProgresses($currentPage, $pagesize, $id) {
        $base = new BaseDAL();
        $limit_start = ($currentPage - 1) * $pagesize;
        $limit_end = $pagesize;
        $sql = "SELECT 
        eucp.*,
        eucp.original_src,
        COUNT(distinct(eucp.user_id)) AS joinPerson
            FROM
            (
                SELECT 
                    c.id,
                    c.`name`,
                    i.original_src,
                    eu.user_id
                from " . $base->table_name("course") . " AS c
                    LEFT JOIN " . $base->table_name("enterprise_course") . " AS ec ON c.id = ec.course_id and ec.`delete`=0
                    LEFT JOIN " . $base->table_name("image") . " AS i ON i.id = c.media_id
                    LEFT JOIN " . $base->table_name("enterprise_user") . " AS eu ON eu.enterprise_id = ec.enterprise_id AND eu.`delete` = 0 AND eu.`status` = 1 and eu.department_id=ec.department_id and eu.position_id=ec.position_id
                    LEFT JOIN " . $base->table_name("user_course") . " AS uc ON uc.course_id = c.id AND eu.user_id = uc.user_id AND uc.`delete` = 0
                    LEFT JOIN " . $base->table_name("enterprise_department") . " AS ed ON ed.id = eu.department_id 
                    LEFT JOIN " . $base->table_name("enterprise_position") . "  AS ep ON ep.id = eu.position_id 
                WHERE
                    ec.enterprise_id = '".$id."' AND c.`delete` = 0
                    and ( ed.delete = 0 or ed.delete is null)
                    and ( ep.delete = 0 or ep.delete is null)
                ) as eucp
            GROUP BY eucp.id
            limit " . $limit_start . "," . $limit_end . " ;";
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
        $sql = "SELECT 
        eucp.*,
        eucp.original_src,
        case when (eucp.tid is not null and eucp.eid is not null) then count(distinct(eucp.user_id)) when (eucp.tid is not null and eucp.eid is null) then 0 else null end as passExam
            FROM
            (
                SELECT 
                    c.id,
                    c.`name`,
                    i.original_src,
                    eu.user_id,
                    l.id as lid,
                    t.id as tid,
                    e.id as eid
                from " . $base->table_name("course") . " AS c
                    LEFT JOIN " . $base->table_name("enterprise_course") . " AS ec ON c.id = ec.course_id and ec.`delete`=0
                    LEFT JOIN " . $base->table_name("image") . " AS i ON i.id = c.media_id
                    LEFT JOIN " . $base->table_name("enterprise_user") . " AS eu ON eu.enterprise_id = ec.enterprise_id AND eu.`delete` = 0 AND eu.`status` = 1 and eu.department_id=ec.department_id and eu.position_id=ec.position_id
                    LEFT JOIN " . $base->table_name("user_course") . " AS uc ON uc.course_id = c.id AND eu.user_id = uc.user_id AND uc.`delete` = 0
                    LEFT JOIN " . $base->table_name("enterprise_department") . " AS ed ON ed.id = eu.department_id 
                    LEFT JOIN " . $base->table_name("enterprise_position") . "  AS ep ON ep.id = eu.position_id 
                    left join " . $base->table_name("lesson") . " as l on l.course_id=ec.course_id and l.delete=0
                    left join " . $base->table_name("test") . " as t on t.lesson_id=l.id and t.delete=0
                    left join " . $base->table_name("exam") . " as e on uc.user_id=e.user_id and c.id=e.course_id and e.point>=c.percentage 
                WHERE
                    ec.enterprise_id = '".$id."' AND c.`delete` = 0
                    and ( ed.delete = 0 or ed.delete is null)
                    and ( ep.delete = 0 or ep.delete is null)
                ) as eucp
            GROUP BY eucp.id
            limit " . $limit_start . "," . $limit_end . " ;";
        
        $res = $base->getFetchAll($sql);
        $total = self::getEnterpriseUserCount($id);
        if (!empty($res)) {
            foreach ($res as $k => $v) {
                $_res[$k] = $v;
                if ($total > 0) {
                    if($v['passExam']==null){
                        $_res[$k]['progress'] = null;
                    }else{
                        $_res[$k]['progress'] = $v['passExam'] / $total * 100;
                    }
                } else {
                    $_res[$k]['progress'] = 0;
                }
            }
            return $_res;
        }
        return false;
    }

}
