<?php

namespace action;

use mod\common as Common;
use TigerDAL;
use TigerDAL\Cms\LessonDAL;
use TigerDAL\Cms\TestDAL;
use config\code;

class test {

    private $class;
    public static $data;
    private $select = ["A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K"];
    private $lesson_id;

    function __construct() {
        $this->class = str_replace('action\\', '', __CLASS__);
        $this->lesson_id = !empty($_GET['lesson_id']) ? $_GET['lesson_id'] : '';
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
            self::$data['total'] = TestDAL::getTotal($keywords, $this->lesson_id);
            self::$data['data'] = TestDAL::getAll($currentPage, $pagesize, $keywords, $this->lesson_id);
            self::$data['class'] = $this->class;
            self::$data['lesson_id'] = $this->lesson_id;
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::CATEGORY_INDEX], code::CATEGORY_INDEX, json_encode($ex));
        }
        \mod\init::getTemplate('admin', $this->class . '_' . __FUNCTION__);
    }

    function getTest() {
        Common::isset_cookie();
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        try {
            if ($id != null) {
                self::$data['data'] = TestDAL::getOne($id);
            } else {
                self::$data['data'] = null;
            }
            self::$data['list'] = LessonDAL::getAll(1, 999, '');
            self::$data['class'] = $this->class;
            self::$data['lesson_id'] = $this->lesson_id;
            self::$data['select'] = $this->select;
            self::$data['option'] = (array) json_decode(self::$data['data']['overview']);
            //Common::pr(self::$data['list']);die;
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::CATEGORY_INDEX], code::CATEGORY_INDEX, json_encode($ex));
        }
        \mod\init::getTemplate('admin', $this->class . '_' . __FUNCTION__);
    }

    function updateTest() {
        Common::isset_cookie();
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        try {
            $overview = '';
            if (!empty($_POST['overview'])) {
                foreach ($_POST['overview'] as $k => $v) {
                    $_overview[$this->select[$k]] = $v;
                }
                $overview = json_encode($_overview, JSON_UNESCAPED_UNICODE);
            }
            if ($id != null) {
                $data = [
                    'lesson_id' => $_POST['lesson_id'],
                    'name' => $_POST['name'],
                    'overview' => $overview,
                    'detail' => $_POST['detail'],
                    'serialization' => $_POST['serialization'],
                    'order_by' => $_POST['order_by'],
                    'edit_by' => Common::getSession("id"),
                    'type' => $_POST['type'],
                    'cat_id' => isset($_POST['cat_id']) ? $_POST['cat_id'] : 0,
                ];
                self::$data = TestDAL::update($id, $data);
            } else {
                if (TestDAL::getByName($_POST['name'])) {
                    Common::js_alert(code::ALREADY_EXISTING_DATA);
                    TigerDAL\CatchDAL::markError(code::$code[code::ALREADY_EXISTING_DATA], code::ALREADY_EXISTING_DATA, json_encode($_POST));
                    Common::js_redir(Common::getSession($this->class));
                }
                //Common::pr(UserDAL::getUser($_POST['name']));die;
                $data = [
                    'lesson_id' => $_POST['lesson_id'],
                    'name' => $_POST['name'],
                    'overview' => $overview,
                    'detail' => $_POST['detail'],
                    'serialization' => $_POST['serialization'],
                    'order_by' => $_POST['order_by'],
                    'add_by' => Common::getSession("id"),
                    'add_time' => date("Y-m-d H:i:s"),
                    'edit_by' => Common::getSession("id"),
                    'edit_time' => date("Y-m-d H:i:s"),
                    'delete' => 0,
                    'type' => $_POST['type'],
                    'cat_id' => isset($_POST['cat_id']) ? $_POST['cat_id'] : 0,
                ];
                self::$data = TestDAL::insert($data);
            }
            if (self::$data) {
                //Common::pr(Common::getSession($this->class));die;
                Common::js_redir(Common::getSession($this->class));
            } else {
                Common::js_alert('修改失败，请联系系统管理员');
            }
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::CATEGORY_UPDATE], code::CATEGORY_UPDATE, json_encode($ex));
        }
    }

    function deleteTest() {
        Common::isset_cookie();
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        try {
            if ($id != null) {
                self::$data = TestDAL::delete($id);
            }
            Common::js_redir(Common::getSession($this->class));
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::CATEGORY_DELETE], code::CATEGORY_DELETE, json_encode($ex));
        }
    }

}
