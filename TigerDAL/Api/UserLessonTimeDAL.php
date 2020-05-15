<?php

namespace TigerDAL\Api;

use TigerDAL\BaseDAL;
use TigerDAL\Cms\LessonDAL as cmsLessonDAL;
use TigerDAL\Api\CourseDAL as apiCourseDAL;

class UserLessonTimeDAL {
    public static $table="user_lesson_time";

    /** 获取用户信息列表
     * @param $currentPage
     * @param $pagesize
     * @param string $keywords
     * @param string $course_id
     * @return array|bool
     */
    public static function getAll($currentPage, $pagesize, $keywords = '') {
        $base = new BaseDAL();
        $limit_start = ($currentPage - 1) * $pagesize;
        $limit_end = $pagesize;
        $where = "";
        if (!empty($keywords)) {
            $where .= " and name like '%" . $keywords . "%' ";
        }
        $sql = "select c.* from " . $base->table_name(self::$table) . " as c "
                . "where c.`delete`=0 " . $where . " "
                . "order by c.order_by asc, c.edit_time desc "
                . "limit " . $limit_start . "," . $limit_end . " ;";
        return $base->getFetchAll($sql);
    }

    /** 获取数量 */
    public static function getTotal($keywords = '') {
        $base = new BaseDAL();
        $where = "";
        if (!empty($keywords)) {
            $where .= " and name like '%" . $keywords . "%' ";
        }
        $sql = "select count(c.*) as total from " . $base->table_name(self::$table) . " as c "
            . "where c.`delete`=0 " . $where . "  ;";
        return $base->getFetchRow($sql)['total'];
    }

    /** 获取用户信息 */
    public static function getOne($id) {
        $base = new BaseDAL();
        $sql = "select c.* from " . $base->table_name(self::$table) . " as c "
                . "where c.`delete`=0 and c.id=" . $id . "  limit 1 ;";
        return $base->getFetchRow($sql);
    }

    /**
     * @param $data
     * @return mixed
     */
    public static function insert($data){
        $base=new BaseDAL();
        $base->insert($data,self::$table);
        return $base->last_insert_id();
    }

    /**
     * @param $id
     * @param $data
     * @return bool|\mysqli_result
     */
    public static function update($id,$data){
        $base=new BaseDAL();
        return $base->update($id,$data,self::$table);
    }

    /**
     * @param $id
     * @return bool|\mysqli_result
     */
    public static function delete($id){
        $base=new BaseDAL();
        $data=['delete'=>0];
        return $base->update($id,$data,self::$table);
    }
}
