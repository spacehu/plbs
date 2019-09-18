<?php

namespace action;

use mod\common as Common;
use TigerDAL;
use TigerDAL\Cms\CourseDAL;
use TigerDAL\Cms\ExaminationDAL;
use TigerDAL\Cms\ExaminationTestDAL;
use TigerDAL\Cms\TestDAL;
use TigerDAL\Cms\EnterpriseDAL;
use config\code;

class examination {

    private $class;
    public static $data;
    private $enterprise_id;

    function __construct() {
        $this->class = str_replace('action\\', '', __CLASS__);
        try {
            $_enterprise = EnterpriseDAL::getByUserId(Common::getSession("id"));
            if (!empty($_enterprise)) {
                $this->enterprise_id = $_enterprise['id'];
            } else {
                if (!empty($_GET['enterprise_id'])) {
                    $this->enterprise_id = $_GET['enterprise_id'];
                } else {
                    Common::js_alert_redir("缺乏参数：enterprise_id", ERROR_405);
                }
            }
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::CATEGORY_INDEX], code::CATEGORY_INDEX, json_encode($ex));
        }
        
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
            self::$data['total'] = ExaminationDAL::getTotal($keywords);
            self::$data['data'] = ExaminationDAL::getAll($currentPage, $pagesize, $keywords);
            self::$data['class'] = $this->class;
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::CATEGORY_INDEX], code::CATEGORY_INDEX, json_encode($ex));
        }
        \mod\init::getTemplate('admin', $this->class . '_' . __FUNCTION__);
    }

    function getExamination() {
        Common::isset_cookie();
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        try {
            if ($id != null) {
                self::$data['data'] = ExaminationDAL::getOne($id);
                self::$data['examination_test'] = ExaminationTestDAL::getAll($id);
            } else {
                self::$data['data'] = null;
                self::$data['examination_test'] = null;
            }
            self::$data['test'] = TestDAL::getExaminationTestList($this->enterprise_id);
            self::$data['class'] = $this->class;
            //Common::pr(self::$data['list']);die;
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::CATEGORY_INDEX], code::CATEGORY_INDEX, json_encode($ex));
        }
        \mod\init::getTemplate('admin', $this->class . '_' . __FUNCTION__);
    }

    function updateExamination() {
        Common::isset_cookie();
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        try {
            if ($id != null) {
                $data = [
                    'name' => $_POST['name'],
                    'percentage' => $_POST['percentage'],
                    'export_count' => $_POST['export_count'],
                    'total' => $_POST['total'],
                    'type' => $_POST['type'],
                    'edit_by' => Common::getSession("id"),
                ];
                self::$data = ExaminationDAL::update($id, $data);
            } else {
                if (ExaminationDAL::getByName($_POST['name'])) {
                    Common::js_alert(code::ALREADY_EXISTING_DATA);
                    TigerDAL\CatchDAL::markError(code::$code[code::ALREADY_EXISTING_DATA], code::ALREADY_EXISTING_DATA, json_encode($_POST));
                    Common::js_redir(Common::getSession($this->class));
                }
                //Common::pr(UserDAL::getUser($_POST['name']));die;
                $data = [
                    'name' => $_POST['name'],
                    'percentage' => $_POST['percentage'],
                    'export_count' => $_POST['export_count'],
                    'total' => $_POST['total'],
                    'type' => $_POST['type'],
                    'add_by' => Common::getSession("id"),
                    'add_time' => date("Y-m-d H:i:s"),
                    'edit_by' => Common::getSession("id"),
                    'edit_time' => date("Y-m-d H:i:s"),
                    'delete' => 0,
                ];
                self::$data = $id = ExaminationDAL::insertExamination($data);
            }
            if (self::$data) {
                $_data = [
                    'add_by' => Common::getSession("id"),
                    'add_time' => date("Y-m-d H:i:s"),
                    'edit_by' => Common::getSession("id"),
                    'edit_time' => date("Y-m-d H:i:s"),
                    'delete' => 0,
                ];
                if (!empty($_POST['examination_test'])) {
                    ExaminationTestDAL::save(array_unique($_POST['examination_test']), $id, $_data);
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

    function deleteExamination() {
        Common::isset_cookie();
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        try {
            if ($id != null) {
                self::$data = ExaminationDAL::delete($id);
            }
            Common::js_redir(Common::getSession($this->class));
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::CATEGORY_DELETE], code::CATEGORY_DELETE, json_encode($ex));
        }
    }

}
