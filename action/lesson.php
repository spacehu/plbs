<?php

namespace action;

use mod\common as Common;
use TigerDAL;
use TigerDAL\Cms\CourseDAL;
use TigerDAL\Cms\LessonDAL;
use TigerDAL\Cms\LessonImageDAL;
use TigerDAL\Cms\ImageDAL;
use config\code;

class lesson {

    private $class;
    public static $data;

    function __construct() {
        $this->class = str_replace('action\\', '', __CLASS__);
    }

    function index() {
        Common::isset_cookie();
        Common::writeSession($_SERVER['REQUEST_URI'], $this->class);
        try {
            $currentPage = isset($_GET['currentPage']) ? $_GET['currentPage'] : 1;
            $pagesize = isset($_GET['pagesize']) ? $_GET['pagesize'] : \mod\init::$config['page_width'];
            $keywords = isset($_GET['keywords']) ? $_GET['keywords'] : "";

            self::$data['currentPage'] = $currentPage;
            self::$data['pagesize'] = $pagesize;
            self::$data['keywords'] = $keywords;
            //Common::pr(self::$data);die;
            self::$data['total'] = LessonDAL::getTotal($keywords);
            self::$data['data'] = LessonDAL::getAll($currentPage, $pagesize, $keywords);
            self::$data['class'] = $this->class;
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::CATEGORY_INDEX], code::CATEGORY_INDEX, json_encode($ex));
        }
        \mod\init::getTemplate('admin', $this->class . '_' . __FUNCTION__);
    }

    function getLesson() {
        Common::isset_cookie();
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        try {
            if ($id != null) {
                self::$data['data'] = LessonDAL::getOne($id);
                self::$data['lesson_image'] = LessonImageDAL::getAll($id);
            } else {
                self::$data['data'] = null;
            }
            self::$data['list'] = CourseDAL::getAll(1, 999, '');
            self::$data['image'] = ImageDAL::getAll(1, 99, '');
            self::$data['class'] = $this->class;
            //Common::pr(self::$data['list']);die;
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::CATEGORY_INDEX], code::CATEGORY_INDEX, json_encode($ex));
        }
        \mod\init::getTemplate('admin', $this->class . '_' . __FUNCTION__);
    }

    function updateLesson() {
        Common::isset_cookie();
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        try {
            if ($id != null) {
                $data = [
                    'course_id' => $_POST['course_id'],
                    'name' => $_POST['name'],
                    'overview' => $_POST['overview'],
                    'detail' => $_POST['detail'],
                    'order_by' => $_POST['order_by'],
                    'edit_by' => Common::getSession("id"),
                    'media_id' => $_POST['media_id'],
                ];
                self::$data = LessonDAL::update($id, $data);
            } else {
                if (LessonDAL::getByName($_POST['name'])) {
                    Common::js_alert(code::ALREADY_EXISTING_DATA);
                    TigerDAL\CatchDAL::markError(code::$code[code::ALREADY_EXISTING_DATA], code::ALREADY_EXISTING_DATA, json_encode($_POST));
                    Common::js_redir(Common::getSession($this->class));
                }
                //Common::pr(UserDAL::getUser($_POST['name']));die;
                $data = [
                    'course_id' => $_POST['course_id'],
                    'name' => $_POST['name'],
                    'overview' => $_POST['overview'],
                    'detail' => $_POST['detail'],
                    'order_by' => $_POST['order_by'],
                    'add_by' => Common::getSession("id"),
                    'add_time' => date("Y-m-d H:i:s"),
                    'edit_by' => Common::getSession("id"),
                    'edit_time' => date("Y-m-d H:i:s"),
                    'delete' => 0,
                    'media_id' => $_POST['media_id'],
                ];
                self::$data = LessonDAL::insert($data);
            }
            if (self::$data) {
                $_data = [
                    'add_by' => Common::getSession("id"),
                    'add_time' => date("Y-m-d H:i:s"),
                    'edit_by' => Common::getSession("id"),
                    'edit_time' => date("Y-m-d H:i:s"),
                    'delete' => 0,
                ];
                if (!empty($_POST['lesson_image'])) {
                    LessonImageDAL::save(array_unique($_POST['lesson_image']), $id, $_data);
                }
                //Common::pr(Common::getSession($this->class));die;
                Common::js_redir(Common::getSession($this->class));
            } else {
                Common::js_alert('修改失败，请联系系统管理员');
            }
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::CATEGORY_UPDATE], code::CATEGORY_UPDATE, json_encode($ex));
        }
    }

    function deleteLesson() {
        Common::isset_cookie();
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        try {
            if ($id != null) {
                self::$data = LessonDAL::delete($id);
            }
            Common::js_redir(Common::getSession($this->class));
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::CATEGORY_DELETE], code::CATEGORY_DELETE, json_encode($ex));
        }
    }

}
