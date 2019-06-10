<?php

namespace TigerDAL\Api;

use TigerDAL\BaseDAL;
use TigerDAL\Api\CourseDAL;

class TestDAL {

    /** 获取用户信息列表 */
    public static function getAll($currentPage, $pagesize, $keywords = '') {
        $base = new BaseDAL();
        $limit_start = ($currentPage - 1) * $pagesize;
        $limit_end = $pagesize;
        $where = "";
        if (!empty($keywords)) {
            $where .= " and name like '%" . $keywords . "%' ";
        }
        $sql = "select * from " . $base->table_name("test") . " where `delete`=0 " . $where . " order by edit_time desc limit " . $limit_start . "," . $limit_end . " ;";
        return $base->getFetchAll($sql);
    }

    /** 获取用户信息 */
    public static function getOne($id) {
        $base = new BaseDAL();
        $sql = "select * from " . $base->table_name("test") . " where `delete`=0 and id=" . $id . "  limit 1 ;";
        return $base->getFetchRow($sql);
    }

    /** 随机出题 */
    public static function getRand($course_id, $limit) {
        $base = new BaseDAL();
        $sql = "select id from " . $base->table_name("lesson") . " where `delete`=0 and course_id=" . $course_id . " ;";
        $_arr = $base->getFetchAll($sql);
        if (!empty($_arr)) {
            foreach ($_arr as $k => $v) {
                $_rows[] = $v['id'];
            }
            $_ids = implode(",", $_rows);
            $_sql = "select * from " . $base->table_name("test") . " where lesson_id in (" . $_ids . ") order by RAND() LIMIT " . $limit . " ;";
            $_res = $base->getFetchAll($_sql);
            if (!empty($_res)) {
                foreach ($_res as $k => $v) {
                    $res[$k] = $v;
                    if ($v['type'] == 'select' || $v['type'] == "selects") {
                        $res[$k]['select'] = json_decode($v['overview']);
                    }
                }
            } else {
                $res = $_res;
            }
            return $res;
        }
        return null;
    }

    /** 答题 */
    public static function joinTest($_data) {
        //分数 是否合格
        $_point = 0;
        $_num = 0;
        $_status = 1;
        $base = new BaseDAL();
        //获取课程信息
        $_course = CourseDAL::getOne($_data['course_id'], $_data['user_id']);
        if (empty($_data['aws'])) {
            return 'emptyparameter';
        }
        //获取题目信息
        foreach ($_data['aws'] as $k => $v) {
            $_obj[] = $k;
        }
        $_test_ids = implode(",", $_obj);
        $_sql = "select * from " . $base->table_name("test") . " where id in (" . $_test_ids . ")  ;";
        $_tests = $base->getFetchAll($_sql);
        if (empty($_data['aws'])) {
            return 'emptydata';
        }
        //算分
        //\mod\common::pr($_data['aws']);die;
        foreach ($_tests as $k => $v) {
            foreach ($_data['aws'] as $key => $val) {
                if ($key == $v['id']) {
                    if (trim($v['serialization']) == trim($val)) {
                        $_num++;
                        $_tests[$k]['scores'] = 1;
                    } else {
                        $_tests[$k]['scores'] = 2;
                    }
                    $_tests[$k]['answer'] = $val;
                }
            }
        }
        $_point = ceil(100 * ($_num / $_course['text_max']));
        //记录答卷
        $data = [
            'user_id' => $_data['user_id'],
            'course_id' => $_data['course_id'],
            'point' => $_point,
            'test_num' => $_course['text_max'],
            'add_time' => $_data['time'],
            'edit_time' => $_data['time'],
            'delete' => 0,
        ];
        $_exam_id = self::insertExamGetId($data);
        //$_exam_id = 1;
        //答题详情
        foreach ($_tests as $k => $v) {
            $_row = [
                'user_id' => $_data['user_id'],
                'course_id' => $_data['course_id'],
                'lesson_id' => $v['lesson_id'],
                'test_id' => $v['id'],
                'scores' => $v['scores'],
                'answer' => $v['answer'],
                'add_time' => $_data['time'],
                'edit_time' => $_data['time'],
                'delete' => 0,
                'exam_id' => $_exam_id,
            ];
            self::insertUserTest($_row);
        }
        //\mod\common::pr($_row);die;
        //课程是否完成
        if ($_point >= 60) {
            $_status = 2;
            $_userCourse = self::updateUserCourse($_data['user_id'], $_data['course_id'], $_status);
        }
        return ['exam' => $data, 'status' => $_status, 'detail' => $_tests];
    }

    /** 记录成绩 */
    public static function insertExamGetId($data) {
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
            $sql = "insert into " . $base->table_name('exam') . " values (null," . $set . ");";
            //\mod\common::pr($sql);die;
            $base->query($sql);
            return $base->last_insert_id();
        } else {
            return true;
        }
    }

    /** 记录成绩详情 */
    public static function insertUserTest($data) {
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
            $sql = "insert into " . $base->table_name('user_test') . " values (null," . $set . ");";
            //\mod\common::pr($sql);die;
            return $base->query($sql);
        } else {
            return true;
        }
    }

    /** 获取用户课程关系 */
    public static function updateUserCourse($user_id, $course_id, $status) {
        $base = new BaseDAL();
        $sql = "update " . $base->table_name("user_course") . " "
                . "set status='" . $status . "' "
                . "where user_id=" . $user_id . " and course_id=" . $course_id . " ;";
        return $base->query($sql);
    }

}
