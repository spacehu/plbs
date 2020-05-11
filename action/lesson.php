<?php

namespace action;

use http\Exception;
use mod\common as Common;
use mod\init;
use TigerDAL\CatchDAL;
use TigerDAL\Cms\CourseDAL;
use TigerDAL\Cms\LessonDAL;
use TigerDAL\Cms\LessonImageDAL;
use TigerDAL\Cms\MediaDAL;
use TigerDAL\Cms\ImageDAL;
use config\code;

class lesson {

    private $class;
    public static $data;
    private $course_id;
    private $cat_id;

    function __construct() {
        $this->class = str_replace('action\\', '', __CLASS__);
        $this->course_id = !empty($_GET['course_id']) ? $_GET['course_id'] : '';
        $this->cat_id = !empty($_GET['cat_id']) ? $_GET['cat_id'] : '';
    }

    function index() {
        Common::isset_cookie();
        Common::writeSession($_SERVER['REQUEST_URI'], $this->class);
        try {
            $currentPage = isset($_GET['currentPage']) ? $_GET['currentPage'] : 1;
            $pagesize = isset($_GET['pagesize']) ? $_GET['pagesize'] : init::$config['page_width'];
            $keywords = isset($_GET['keywords']) ? $_GET['keywords'] : "";

            self::$data['currentPage'] = $currentPage;
            self::$data['pagesize'] = $pagesize;
            self::$data['keywords'] = $keywords;
            //Common::pr(self::$data);die;
            self::$data['total'] = LessonDAL::getTotal($keywords, $this->course_id);
            self::$data['data'] = LessonDAL::getAll($currentPage, $pagesize, $keywords, $this->course_id);
            self::$data['class'] = $this->class;
            self::$data['course_id'] = $this->course_id;
            self::$data['cat_id'] = $this->cat_id;
        } catch (Exception $ex) {
            CatchDAL::markError(code::$code[code::CATEGORY_INDEX], code::CATEGORY_INDEX, json_encode($ex));
        }
        init::getTemplate('admin', $this->class . '_' . __FUNCTION__);
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
                self::$data['lesson_image'] = null;
            }
            self::$data['list'] = CourseDAL::getAll(1, 999, '');
            self::$data['image'] = ImageDAL::getAll(1, 99, '');
            self::$data['media'] = MediaDAL::getAll(1, 99, '', '');
            //self::$data['media'] = MediaDAL::getAll(1, 99, '', self::$data['data']['type']);
            self::$data['class'] = $this->class;
            self::$data['course_id'] = $this->course_id;
            self::$data['config'] = init::$config['env'];
            //Common::pr(self::$data['list']);die;
        } catch (Exception $ex) {
            CatchDAL::markError(code::$code[code::CATEGORY_INDEX], code::CATEGORY_INDEX, json_encode($ex));
        }
        init::getTemplate('admin', $this->class . '_' . __FUNCTION__);
    }

    function updateLesson() {
        Common::isset_cookie();
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        try {
            if($_POST['edit_doc'] === "0"){
                $media_id="0";
            }else {
                $material = new material();
                $media_id = $material->_saveMedia($_POST['edit_doc'], $_POST['type']);
                //var_dump($media_id);die;
            }
            //common::pr($_POST['edit_doc']);die;
            if (LessonDAL::getByName($_POST['name'], $_POST['course_id'], ($id==null)?0:$id)) {
                Common::js_alert(code::ALREADY_EXISTING_DATA);
                CatchDAL::markError(code::$code[code::ALREADY_EXISTING_DATA], code::ALREADY_EXISTING_DATA, json_encode($_POST));
                Common::js_redir(Common::getSession($this->class));
            }
            if ($id != null) {
                $data = [
                    'course_id' => $_POST['course_id'],
                    'name' => $_POST['name'],
                    'overview' => $_POST['overview'],
                    'detail' => $_POST['detail'],
                    'order_by' => $_POST['order_by'],
                    'edit_by' => Common::getSession("id"),
                    'media_id' => $media_id,
                    'type' => $_POST['type'],
                ];
                self::$data = LessonDAL::update($id, $data);
            } else {
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
                    'media_id' => $media_id,
                    'type' => $_POST['type'],
                ];
                self::$data = $id = LessonDAL::insertLesson($data);
                //echo $id;die;
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
                    $_POST['lesson_image']=array_unique($_POST['lesson_image']);
                }
                //Common::pr($_POST['lesson_image']);die;
                LessonImageDAL::save($_POST['lesson_image'], $id, $_data);
                //Common::pr(Common::getSession($this->class));die;
                Common::js_redir(Common::getSession($this->class));
            } else {
                Common::js_alert('修改失败，请联系系统管理员');
            }
        } catch (Exception $ex) {
            CatchDAL::markError(code::$code[code::CATEGORY_UPDATE], code::CATEGORY_UPDATE, json_encode($ex));
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
            CatchDAL::markError(code::$code[code::CATEGORY_DELETE], code::CATEGORY_DELETE, json_encode($ex));
        }
    }

}
