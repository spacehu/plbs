<?php

namespace TigerDAL\Cms;

use TigerDAL\BaseDAL;

class UserInfoLessonTimeDAL {

    /** 获取用户信息 */
    public static function getOneByUserId($userid) {
        $base = new BaseDAL();
        $sql = "select * from " . $base->table_name("user_lesson_time") . " 
                    where `delete`=0 and user_id = '" . $userid . "' 
                    order by `status` desc
                    limit 1 ;";
        return $base->getFetchRow($sql);
    }

    /** 插入 */
    public static function insertUserLessonTime($data) {
        $base = new BaseDAL();
        $base->insert($data,"user_lesson_time");
        return $base->last_insert_id();
    }
}
