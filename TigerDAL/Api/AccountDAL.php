<?php

namespace TigerDAL\Api;

use TigerDAL\BaseDAL;
use TigerDAL\Cms\UserDAL;

/*
 * 用来返回生成首页需要的数据
 * 类
 * 访问数据库用
 * 继承数据库包
 */

class AccountDAL {

    function __construct() {
        
    }

    /** 新建收藏 */
    public static function insertUserFavorites($data) {
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
            $sql = "insert into " . $base->table_name('user_favorites') . " values (null," . $set . ");";
            return $base->query($sql);
        } else {
            return true;
        }
    }

    /** 更新收藏 */
    public static function updateUserFavorites($id, $data) {
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
            $sql = "update " . $base->table_name('user_favorites') . " set " . $set . "  where id=" . $id . " ;";
            return $base->query($sql);
        } else {
            return true;
        }
    }

    /** 收藏的反复方法 */
    public static function doFavorites($user_id, $article_id) {
        $base = new BaseDAL();
        $sql = "select * from " . $base->table_name('user_favorites') . " where user_id=" . $user_id . " and article_id=" . $article_id . " ;";
        $row = $base->getFetchRow($sql);
        if (empty($row)) {
            $_data = [
                'user_id' => $user_id,
                'article_id' => $article_id,
                'add_time' => date("Y-m-d H:i:s"),
                'edit_time' => date("Y-m-d H:i:s"),
                'delete' => 0,
            ];
            self::insertUserFavorites($_data);
        } else {
            if ($row['delete'] == 0) {
                $_data = ['delete' => '1'];
            } else {
                $_data = ['delete' => '0'];
            }
            self::updateUserFavorites($row['id'], $_data);
        }
        return true;
    }

    /** 获取已读课程信息列表 */
    public static function getCourses($currentPage, $pagesize, $user_id) {
        $base = new BaseDAL();
        $limit_start = ($currentPage - 1) * $pagesize;
        $limit_end = $pagesize;
        $sql = "select c.*,uc.status as ucStatus,i.original_src,count(l.id) as ls,count(ul.id) as uls "
                . "from " . $base->table_name("user_course") . " as uc "
                . "left join " . $base->table_name("course") . " as c on c.id=uc.course_id "
                . "left join " . $base->table_name("image") . " as i on i.id=c.media_id "
                . "LEFT JOIN " . $base->table_name("lesson") . " AS l ON l.course_id = c.id AND l.`delete` = 0 "
                . "LEFT JOIN " . $base->table_name("user_lesson") . " AS ul ON ul.lesson_id = l.id AND ul.`delete` = 0 "
                . "where uc.`delete`=0 and c.`delete`=0 and uc.user_id=" . $user_id . " "
                . "GROUP BY c.id "
                . "order by uc.id desc "
                . "limit " . $limit_start . "," . $limit_end . " ;";
        //echo $sql;
        return $base->getFetchAll($sql);
    }

    /** 获取已读课程信息列表 total */
    public static function getCoursesTotal($user_id) {
        $base = new BaseDAL();
        $sql = "select count(uc.id) as num from " . $base->table_name("user_course") . " as uc "
                . "left join " . $base->table_name("course") . " as c on c.id=uc.course_id "
                . "where uc.`delete`=0 and c.`delete`=0 and uc.user_id=" . $user_id . " ;";
        return $base->getFetchRow($sql)['num'];
    }

    /** 收藏的文章列表 */
    public static function getFavorites($currentPage, $pagesize, $user_id) {
        $base = new BaseDAL();
        $limit_start = ($currentPage - 1) * $pagesize;
        $limit_end = $pagesize;
        $sql = "select c.*,i.original_src from " . $base->table_name("user_favorites") . " as uf "
                . "left join " . $base->table_name("article") . " as c on c.id=uf.article_id "
                . "left join " . $base->table_name("image") . " as i on i.id=c.media_id "
                . "where uf.`delete`=0 and c.`delete`=0 and uf.user_id=" . $user_id . " "
                . "order by uf.id desc "
                . "limit " . $limit_start . "," . $limit_end . " ;";
        return $base->getFetchAll($sql);
    }

    /** 收藏的文章列表 total */
    public static function getFavoritesTotal($user_id) {
        $base = new BaseDAL();
        $sql = "select count(uf.id) as num from " . $base->table_name("user_favorites") . " as uf "
                . "left join " . $base->table_name("article") . " as c on c.id=uf.article_id "
                . "where uf.`delete`=0 and c.`delete`=0 and uf.user_id=" . $user_id . " ;";
        return $base->getFetchRow($sql)['num'];
    }

    /** 收藏的文章列表 */
    public static function getFavorite($user_id, $article_id) {
        $base = new BaseDAL();
        $sql = "select uf.* from " . $base->table_name("user_favorites") . " as uf "
                . "where uf.user_id=" . $user_id . " and uf.article_id=" . $article_id . " ;";
        return $base->getFetchRow($sql);
    }

}
