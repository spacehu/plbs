<?php

namespace TigerDAL\Cms;

use TigerDAL\BaseDAL;

class StatisticsDAL {

    /** 获取pv列表 */
    public static function getPageView($_startTime, $_endTime, $_model = "", $_url = "") {
        $_and = "";
        return self::query($_and, $_startTime, $_endTime, $_model, $_url);
    }

    /** 获取iv列表 */
    public static function getIPView($_startTime, $_endTime, $_model = "", $_url = "") {
        $_and = "";
        $_groupby = "group by ip ";
        return self::query($_and, $_startTime, $_endTime, $_model, $_url, $_groupby);
    }

    /** 获取uv列表  */
    public static function getUserView($_startTime, $_endTime, $_model = "", $_url = "") {
        $_and = "and user_id is not null and user_id <> '' ";
        $_groupby = "group by user_id ";
        return self::query($_and, $_startTime, $_endTime, $_model, $_url, $_groupby);
    }

    private static function query($_and, $_startTime, $_endTime, $_model, $_url, $_groupby = "") {
        $base = new BaseDAL();
        $_and .= !empty($_model) ? "and `model`='" . $_model . "' " : "";
        $_and .= !empty($_url) ? "and `page_url`='" . $_url . "' " : "";
        $sql = "select count(1) as num,A.time "
                . "FROM ( "
                . "SELECT LEFT (add_time, 10) AS time "
                . "from " . $base->table_name('access_log') . " "
                . "where `add_time`>='" . $_startTime . "' "
                . "and `add_time`<'" . $_endTime . "' "
                . $_and
                . $_groupby
                . ") AS A "
                . "GROUP BY A.time; ";
        return $base->getFetchAll($sql);
    }

    /** 获取抽奖类型 */
    public static function getSource() {
        $base = new BaseDAL();
        $sql = "select source "
                . "FROM " . $base->table_name('leave_message') . " "
                . "group by source"
                . "; ";
        return $base->getFetchAll($sql);
    }

    /** 获取参与信息 */
    public static function getBonus($currentPage, $pagesize, $_startTime, $_endTime, $_source) {
        $base = new BaseDAL();
        $limit_start = ($currentPage - 1) * $pagesize;
        $limit_end = $pagesize;
        $sql = "select * "
                . "FROM " . $base->table_name('leave_message') . " "
                . "where `add_time`>='" . $_startTime . "' "
                . "and `add_time`<'" . $_endTime . "' "
                . "and `source`='" . $_source . "' "
                . "order by code desc "
                . "limit " . $limit_start . "," . $limit_end . " "
                . "; ";
        return $base->getFetchAll($sql);
    }

    /** 获取数量 */
    public static function getBonusTotal($_startTime, $_endTime, $_source) {
        $base = new BaseDAL();
        $sql = "select count(1) as total "
                . "FROM " . $base->table_name('leave_message') . " "
                . "where `add_time`>='" . $_startTime . "' "
                . "and `add_time`<'" . $_endTime . "' "
                . "and `source`='" . $_source . "' "
                . "order by code desc "
                . "limit 1 "
                . "; ";
        return $base->getFetchRow($sql)['total'];
    }

    /** 获取粉丝信息-男女比例 */
    public static function getSex() {
        $base = new BaseDAL();
        $sql = "select count(1) as num,sex "
                . "FROM " . $base->table_name('user_info') . " "
                . "group by sex "
                . "; ";
        return $base->getFetchAll($sql);
    }

    /** 获取粉丝信息-年龄区间 
      SELECT
      SUM(A.num) AS num,
      CASE
      WHEN age <= 18 THEN
      'c1'
      WHEN (age <= 24 AND age > 19) THEN
      'c2'
      WHEN (age <= 30 AND age > 25) THEN
      'c3'
      WHEN (age <= 40 AND age > 31) THEN
      'c4'
      WHEN age > 40 THEN
      'c5'
      ELSE
      'c0'
      END AS `nld`
      FROM
      (
      SELECT
      count(1) AS num,
      (
      YEAR (NOW()) - YEAR (DATE(brithday))
      ) AS age
      FROM
      mrhu_user_info
      GROUP BY
      age
      ) AS A
      GROUP BY
      nld;
     * @return type
     */
    public static function getAge() {
        $base = new BaseDAL();
        $sql = "SELECT SUM(A.num) as num, "
                . "CASE when age<=18 then 'c1' when (age<=24 and age>=19) then 'c2' when (age<=30 and age>=25) then 'c3' when (age<=40 and age>=31) then 'c4' when age>40 then 'c5' else  'c0' end as `nld` "
                . "FROM	( "
                . "SELECT count(1) AS num,(YEAR (NOW()) - YEAR (DATE(brithday))) AS age "
                . "FROM " . $base->table_name('user_info') . " "
                . "GROUP BY age "
                . ") AS A "
                . "GROUP BY nld "
                . "; ";
        return $base->getFetchAll($sql);
    }

    /** 获取粉丝信息-居住省份
      SELECT
      count(1) AS num,
      rc.`NAME`
      FROM
      mrhu_user_info AS ui,
      mrhu_region_china AS rc
      WHERE
      ui.province = rc.id
      GROUP BY
      rc.`NAME`;
     */
    public static function getRegion() {
        $base = new BaseDAL();
        $sql = "select count(1) as num,rc.`name` "
                . "FROM " . $base->table_name('user_info') . " as ui "
                . ", " . $base->table_name('region_china') . " as rc "
                . "where ui.province=rc.id and ui.province is not null and ui.province <> 0 "
                . "group by rc.`name` "
                . "; ";
        return $base->getFetchAll($sql);
    }

    /** 获取成员在线学习详细 */
    public static function getCustomerInfo($id){
        $base = new BaseDAL();
        $sql="SELECT 
                    euce.id,
                    euce.uname as `name`,
                    euce.photo,
                    euce.phone,
                    euce.edname,
                    euce.epname,
                    count(DISTINCT (euce.eccid)) as enterpriseCourseCount,
                    COUNT(DISTINCT (case when (euce.eccid = euce.course_id
                        AND euce.enterpriseCourseJoinCourseId IS NOT NULL)
                        OR (euce.eccid <> euce.course_id AND euce.enterpriseCourseJoinCourseId IS  NULL) then euce.course_id else null end )) AS joinCourseCount,
                    COUNT(DISTINCT (euce.eid)) AS passExamCount,
                    COUNT( (euce.ulid)) AS userLessonTotal,
                    COUNT( (euce.lid)) AS lessonCount,
                    IF(COUNT(euce.lid) <> 0,
                        COUNT(euce.ulid) / COUNT(euce.lid) * 100,
                        0) AS progress,
                    null as `hours`
                        from 
                (
                SELECT 
                    u.id,
                    u.`name` as uname,
                    u.photo,
                    u.phone,
                    uc.course_id,
                    eu.department_id as eudid,
                    ed.`name` as edname,
                    eu.position_id as eupid,
                    ep.`name` as epname,
                    ec.course_id as eccid,
                    ec.department_id as ecdid,
                    ec.position_id as ecpid,
                    ed.`delete` as edd,
                    ep.`delete` as epd,
                    e.id as eid,e.`point` as epoint,
                    c.percentage,
                    l.id as lid,
                    ul.id as ulid,
                    ecS.course_id as enterpriseCourseJoinCourseId
                
                FROM
                " . $base->table_name("user_info") . " AS u    
                    LEFT JOIN " . $base->table_name("enterprise_user") . " AS eu ON u.id = eu.user_id
                        LEFT JOIN " . $base->table_name("user_course") . " AS uc ON uc.user_id = eu.user_id AND uc.`delete` = 0 
                        left join " . $base->table_name("course") . " as c on uc.course_id=c.id and c.`delete` =0 
                        left join " . $base->table_name("enterprise_course") . " as ec on ec.enterprise_id=eu.enterprise_id and ec.`delete`=0 
                        LEFT JOIN " . $base->table_name("enterprise_department") . " AS ed ON ed.id = eu.department_id 
                        LEFT JOIN " . $base->table_name("enterprise_position") . " AS ep ON ep.id = eu.position_id 
                        
                        LEFT JOIN " . $base->table_name("exam") . " AS e ON e.course_id = uc.course_id and e.user_id = u.id AND e.`point` > c.percentage
                        LEFT JOIN " . $base->table_name("lesson") . " AS l ON l.course_id = uc.course_id and l.`delete`=0
                        LEFT JOIN " . $base->table_name("user_lesson") . " AS ul ON l.id = ul.lesson_id and ul.`delete`=0 and ul.user_id=u.id
                        LEFT JOIN " . $base->table_name("enterprise_course") . " AS ecS ON ecS.enterprise_id = eu.enterprise_id
                            AND ecS.`delete` = 0 and ecS.course_id=c.id
                WHERE
                    eu.`delete` = 0 AND eu.`STATUS` = 1
                        and u.id in (".$id.")
                        and (ec.department_id is null  or (ec.department_id is not null and ed.delete = 0 and (ec.department_id=eu.department_id or ec.department_id=0)))
                        and (ec.position_id is null  or (ec.position_id is not null and ep.delete = 0 and (ec.position_id=eu.position_id or ec.position_id=0)))
                order by u.id asc
                ) as euce
                group by euce.id
                order by euce.id asc;";
            // echo $sql;die;
            // AND eu.enterprise_id = '".$id."'
        $res = $base->getFetchRow($sql);
        //var_dump($res);die;
        return $res;
    }

    /** 获取成员在线学习详细 课程列表 */
    public static function getCustomerCourseList($id){
        $base = new BaseDAL();
        $sql="SELECT 
                    euce.course_id as id,
                    euce.cname as `name`,
                    case when euce.eccid = euce.course_id then 1 else 0 end AS enterpriseCourse,
                    COUNT(DISTINCT (euce.eid)) AS passExamCount,
                    COUNT( (euce.ulid)) AS userLessonTotal,
                    COUNT( (euce.lid)) AS lessonCount,
                    IF(COUNT(euce.lid) <> 0,
                        COUNT(euce.ulid) / COUNT(euce.lid) * 100,
                        0) AS progress,
                    ec.id
                        from 
                (
                SELECT 
                    u.id,
                    u.`NAME` AS uname,
                    u.photo,
                    ec.course_id as eccid,
                    c.id as course_id,
                    eu.enterprise_id as eueid,
                    eu.department_id AS eudid,
                    ed.`name` AS edname,
                    eu.position_id AS eupid,
                    ep.`name` AS epname,
                    ec.department_id AS ecdid,
                    ec.position_id AS ecpid,
                    ed.`delete` AS edd,
                    ep.`delete` AS epd,
                    e.id AS eid,
                    e.`point` AS epoint,
                    c.percentage,
                    c.`name` AS cname,
                    l.id AS lid,
                    ul.id AS ulid
                
                FROM
                " . $base->table_name("user_info") . " AS u    
                    LEFT JOIN " . $base->table_name("enterprise_user") . " AS eu ON u.id = eu.user_id
                    LEFT JOIN " . $base->table_name("enterprise_department") . " AS ed ON ed.id = eu.department_id 
                    LEFT JOIN " . $base->table_name("enterprise_position") . " AS ep ON ep.id = eu.position_id 
                        LEFT JOIN " . $base->table_name("user_course") . " AS uc ON uc.user_id = eu.user_id AND uc.`delete` = 0 
                        left join " . $base->table_name("enterprise_course") . " as ec on ec.enterprise_id=eu.enterprise_id and ec.`delete`=0 
                        left join " . $base->table_name("course") . " as c on (uc.course_id = c.id or ec.course_id = c.id) and c.`delete` =0 
                        
                        LEFT JOIN " . $base->table_name("exam") . " AS e ON e.course_id = c.id and e.user_id = u.id AND e.`point` > c.percentage
                        LEFT JOIN " . $base->table_name("lesson") . " AS l ON l.course_id = c.id and l.`delete`=0
                        LEFT JOIN " . $base->table_name("user_lesson") . " AS ul ON l.id = ul.lesson_id and ul.`delete`=0 and ul.user_id=u.id
                WHERE
                    eu.`delete` = 0 AND eu.`STATUS` = 1
                        and u.id in (".$id.")
                        and (ec.department_id is null  or (ec.department_id is not null and ed.delete = 0 and (ec.department_id=eu.department_id or ec.department_id=0)))
                        and (ec.position_id is null  or (ec.position_id is not null and ep.delete = 0 and (ec.position_id=eu.position_id or ec.position_id=0)))
                order by u.id asc
                ) as euce
                left join mrhu_enterprise_course as ec on ec.course_id= euce.course_id and ec.enterprise_id=euce.eueid and ec.delete =0 
                where (euce.eccid = euce.course_id and ec.id is not null ) or (euce.eccid <> euce.course_id AND ec.id IS NULL)
                group by euce.course_id
                order by euce.course_id asc;";
        // echo $sql;die;
        // AND eu.enterprise_id = '".$id."'
        $res = $base->getFetchAll($sql);
        // \mod\common::pr($res);die;
        return $res;
    }

    /** 获取在线课程学习详细  */
    public static function getCourseInfo($id){
        $base = new BaseDAL();
        $sql = "SELECT 
        eucp.id,eucp.name,eucp.ecid,eucp.user_id,
        eucp.original_src,
        COUNT(distinct(eucp.user_id)) AS joinPerson,
        avg(case when eucp.totalL>0 then eucp.totalUl/eucp.totalL else 0 end ) as progressLesson,
        avg(case when eucp.totalE>0 then eucp.totalEU/eucp.totalE else 0 end ) as progressExam
            FROM
            (
                SELECT 
                c.id, c.`name`, i.original_src, ec.id as ecid,eu.user_id as user_id,
                case when eu.user_id=uc.user_id then count(distinct(l.id)) else 0 end as totalL,
                case when eu.user_id=uc.user_id then count(distinct(ul.id)) else 0 end as totalUL,
                count(distinct(eu.user_id)) as totalEU,
                count(distinct(e.user_id)) as totalE
                from " . $base->table_name("course") . " AS c
                    LEFT JOIN " . $base->table_name("enterprise_course")." AS ec ON c.id = ec.course_id AND ec.`delete` = 0
                    LEFT JOIN " . $base->table_name("image")." AS i ON i.id = c.media_id
                    LEFT JOIN " . $base->table_name("user_course")." AS uc ON uc.course_id = c.id
                        AND uc.`delete` = 0
                    LEFT JOIN " . $base->table_name("enterprise_user")." AS eu ON eu.enterprise_id = ec.enterprise_id
                        AND eu.`delete` = 0
                        AND eu.`status` = 1
                        AND eu.user_id = uc.user_id
                        AND (eu.department_id = ec.department_id or (ec.department_id=0))
                        AND (eu.position_id = ec.position_id or (ec.position_id=0)) 
                    LEFT JOIN " . $base->table_name("enterprise_department")." AS ed ON ed.id = eu.department_id
                    LEFT JOIN " . $base->table_name("enterprise_position")." AS ep ON ep.id = eu.position_id
                    left join " . $base->table_name("lesson")." as l on c.id=l.course_id and l.delete=0
                    left join " . $base->table_name("user_lesson")." as ul on ul.user_id=uc.user_id and ul.lesson_id=l.id and ul.delete=0
                    left join " . $base->table_name("exam")." as e on e.user_id=eu.user_id and e.course_id=c.id and e.delete=0 and e.point>= c.percentage 
                WHERE
                    c.id = '".$id."' AND c.`delete` = 0
                    and ( ed.delete = 0 or ed.delete is null)
                    and ( ep.delete = 0 or ep.delete is null)
                    group by ec.id
                ) as eucp
            GROUP BY eucp.id;";
            //echo $sql;die;
            // AND eu.enterprise_id = '".$id."'
        $res = $base->getFetchRow($sql);
        //var_dump($res);die;
        return $res;
    }


    /** 获取在线课程学习详细 学员列表 */
    public static function getCourseCustomerList($id){
        $base = new BaseDAL();
        $sql = "
            select 
                ccl.id,ccl.name,ccl.edname,ccl.epname,ccl.totalUL,ccl.totalL,ccl.totalE,
                case when ccl.totalL>0 then ccl.totalUL/ccl.totalL else 0 end as progressLesson 
            from (
            select 
                ui.id,
                ui.name,
                ed.name as edname,
                ep.name as epname,
                count(ul.id) as totalUL,
                count(l.id) as totalL,
                count(e.id) as totalE
            from 
            " . $base->table_name("user_info")." as ui 
            left join " . $base->table_name("user_course")." as uc on ui.id=uc.user_id and uc.delete=0 
            left join " . $base->table_name("course")." as c on uc.course_id=c.id and c.delete=0
            left join " . $base->table_name("enterprise_user")." as eu on ui.id=eu.user_id and eu.delete=0
            left join " . $base->table_name("enterprise_course")." as ec on eu.enterprise_id=ec.enterprise_id
                and uc.course_id=ec.course_id
                and (
                (ec.department_id=0 and ec.position_id=0) 
                or (ec.department_id=eu.department_id and ec.position_id=0)
                or (ec.department_id=eu.department_id and ec.position_id=eu.position_id)
                )
            left join " . $base->table_name("enterprise_department")." as ed on ed.id = eu.department_id 
            left join " . $base->table_name("enterprise_position")." as ep on ep.id = eu.position_id 
            LEFT JOIN " . $base->table_name("lesson")." AS l ON c.id = l.course_id AND l.delete = 0
            LEFT JOIN " . $base->table_name("user_lesson")." AS ul ON ul.user_id = uc.user_id
                AND ul.lesson_id = l.id
                AND ul.delete = 0
            LEFT JOIN " . $base->table_name("exam")." AS e ON e.user_id = eu.user_id
                AND e.course_id = c.id
                AND e.delete = 0
                AND e.point >= c.percentage
            where uc.course_id=".$id."
                and ec.delete=0
                AND (ed.delete = 0 OR ed.delete IS NULL)
                AND (ep.delete = 0 OR ep.delete IS NULL)
                group by ui.id
            ) as ccl";
        // echo $sql;die;
        // AND eu.enterprise_id = '".$id."'
        $res = $base->getFetchAll($sql);
        // \mod\common::pr($res);die;
        return $res;
    }

    /** 试卷统计列表 */
    public static function getExaminationList($currentPage, $pagesize, $id){
        $base = new BaseDAL();
        $limit_start = ($currentPage - 1) * $pagesize;
        $limit_end = $pagesize;
        $sql=" 
            select 
                es.id,
                es.name,
                count(distinct(es.exid)) as totalEx,
                count(distinct(if(es.pass=1,es.exid,null))) as totalExPass,
                count(distinct(es.user_id)) as totalEu,
                count(distinct(if(es.pass=1,es.user_id,null))) as totalEuPass
            from (
                select 
                    e.id,e.name,e.percentage,ex.id as exid,ex.point,eu.user_id,
                    case when e.percentage<=ex.point then 1 else 0 end as pass 
                from ".$base->table_name("examination")." as e 
                left join ".$base->table_name("exam")." as ex on e.id=ex.examination_id and ex.delete=0
                left join ".$base->table_name("enterprise_user")." as eu on ex.user_id=eu.user_id and eu.delete=0 and eu.status=1
                where e.enterprise_id=".$id." and e.delete=0 
            ) as es
            group by es.id
            limit " . $limit_start . "," . $limit_end . " ;";
        //echo $sql;
        $res=$base->getFetchAll($sql);
        return $res;
    }

    /** 试卷统计列表 */
    public static function getExaminationListTotal($id){
        $base = new BaseDAL();
        $sql=" select count(1) as total from ".$base->table_name("examination")." as e where e.enterprise_id=".$id." and e.delete=0  ;";
        $res=$base->getFetchRow($sql);
        return $res['total'];
    }


    /******* 暂废弃 **********************************************/
    /** 成员在线学习 */
    public static function getCustomerList($currentPage, $pagesize, $keywords, $enterprise_id, $_startTime, $_endTime) {
        $base = new BaseDAL();
        $limit_start = ($currentPage - 1) * $pagesize;
        $limit_end = $pagesize;
        $and = "";
        if (!empty($keywords)) {
            $and .= " and ui.name like '%" . $keywords . "%' ";
        }
        if (!empty($_startTime)) {
            $and .= " and ui.last_login_time >= '" . $_startTime . "' ";
        }
        if (!empty($_endTime)) {
            $and .= " and ui.last_login_time <= '" . $_endTime . "' ";
        }
        $middle = "from " . $base->table_name("user_info") . " as ui "
                . "right join " . $base->table_name("enterprise_user") . " as eu on ui.id=eu.user_id "
                . "left join " . $base->table_name("user_course") . " as uc on ui.id=uc.user_id "
                . "left join " . $base->table_name("enterprise_course") . " as ec on uc.course_id=ec.course_id and ec.enterprise_id= eu.enterprise_id "
                . " where eu.status=1 and eu.enterprise_id=" . $enterprise_id . "  " . $and . " "
                . "GROUP BY ui.id ";

        $sql = "select "
                . "ui.id,ui.`name`,ui.last_login_time, "
                . "count(uc.id) AS learned, "
                . "case when uc.`status`=2 then count(uc.id) else 0 end  AS finished, "
                . "case when ec.enterprise_id is not null then count(ec.enterprise_id) else 0 end AS necessary, "
                . "(count(uc.id) - case when ec.enterprise_id is not null then count(ec.enterprise_id) else 0 end) AS unnecessary "
                . $middle
                . "order by ui.edit_time desc "
                . "limit " . $limit_start . "," . $limit_end . " ;";
        //echo $sql;die;
        $data = $base->getFetchAll($sql);
        $sql = "select count(*) as total from ( "
                . "select count(1) as t "
                . $middle
                . " ) as os ;";
        //echo $sql;die;
        $total = $base->getFetchRow($sql)['total'];
        return ['data' => $data, 'total' => $total];
    }

    /** 员工信息维护 */
    public static function getUserList($currentPage, $pagesize, $keywords, $enterprise_id) {
        $base = new BaseDAL();
        $limit_start = ($currentPage - 1) * $pagesize;
        $limit_end = $pagesize;
        $and = "";
        if (!empty($keywords)) {
            $and .= " and u.name like '%" . $keywords . "%' ";
        }
        $middle = "from " . $base->table_name("user") . " as u "
                . "left join " . $base->table_name("role") . " as r on u.role_id=r.id "
                . " where u.`delete`=0 and u.enterprise_id=" . $enterprise_id . "  " . $and . " "
                . "";

        $sql = "select "
                . "u.id,u.`name`,u.email,u.add_time,r.`name` as rName "
                . $middle
                . "order by u.edit_time desc "
                . "limit " . $limit_start . "," . $limit_end . " ;";
        //echo $sql;die;
        $data = $base->getFetchAll($sql);
        $sql = "select count(1) as total "
                . $middle
                . " ;";
        //echo $sql;die;
        $total = $base->getFetchRow($sql)['total'];
        return ['data' => $data, 'total' => $total];
    }

    /** 在线课程学习 */
    public static function getCourseList($currentPage, $pagesize, $keywords, $enterprise_id, $_startTime, $_endTime) {
        $base= new BaseDAL();
        $limit_start = ($currentPage - 1) * $pagesize;
        $limit_end = $pagesize;
        $and = "";
        if (!empty($keywords)) {
            $and .= " and c.name like '%" . $keywords . "%' ";
        }
        if (!empty($_startTime)) {
            $and .= " and c.add_time >= '" . $_startTime . "' ";
        }
        if (!empty($_endTime)) {
            $and .= " and c.add_time <= '" . $_endTime . "' ";
        }
        $middle = "from " . $base->table_name("course") . " as c "
                . "left join " . $base->table_name("image") . " as i on i.id=c.media_id "
                . "left join " . $base->table_name("category") . " as cat on cat.id=c.category_id "
                . "left join " . $base->table_name("enterprise_course") . " as ec on c.id=ec.course_id "
                . "left join " . $base->table_name("enterprise_user") . " as  eu on ec.enterprise_id=eu.enterprise_id and ec.`delete`=0 "
                . "left join " . $base->table_name("user_course") . " as uc on c.id=uc.course_id and eu.user_id=uc.user_id and uc.`delete`=0 "
                . "left join " . $base->table_name("exam") . " as e on c.id=e.course_id and e.user_id=eu.user_id and e.`delete`=0 "
                . " where ec.enterprise_id=" . $enterprise_id . "  " . $and . " "
                . "GROUP BY c.id ";

        $sql = "select "
                . "c.id,c.`name`,c.add_time,cat.`name` as catName,i.original_src,"
                . "count(DISTINCT e.user_id) as userCount,"
                . "sum(case when e.point >= c.percentage then 1 else 0 end) as userPassCount,"
                . "case when count(e.user_id)>0 then (sum(case when e.point >= c.percentage then 1 else 0 end)/count(DISTINCT e.user_id))*100 else 0 end as userPassPercent "
                . $middle
                . "order by c.edit_time desc "
                . "limit " . $limit_start . "," . $limit_end . " ;";
        //echo $sql;die;
        $data = $base->getFetchAll($sql);
        $sql = "select count(*) as total from ( "
                . "select count(1) as t "
                . $middle
                . " ) as os ;";
        //echo $sql;die;
        $total = $base->getFetchRow($sql)['total'];
        return ['data' => $data, 'total' => $total];
    }

}
