<?php

namespace action;

use mod\common as Common;
use TigerDAL;
use TigerDAL\Cms\UserInfoDAL;
use TigerDAL\Cms\EnterpriseDAL;
use TigerDAL\Cms\CourseDAL;
use config\code;

class customer {

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
                $this->enterprise_id = '';
            }
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::CATEGORY_INDEX], code::CATEGORY_INDEX, json_encode($ex));
        }
    }

    function index() {
        //Common::pr(date("Y-m-d H:i:s"));die;
        Common::isset_cookie();
        Common::writeSession($_SERVER['REQUEST_URI'], $this->class);
        //Common::pr(Common::getSession($this->class));die;
        $currentPage = isset($_GET['currentPage']) ? $_GET['currentPage'] : 1;
        $pagesize = isset($_GET['pagesize']) ? $_GET['pagesize'] : \mod\init::$config['page_width'];
        $keywords = isset($_GET['keywords']) ? $_GET['keywords'] : "";
        try {
            self::$data['data'] = UserInfoDAL::getAll($currentPage, $pagesize, $keywords, $this->enterprise_id);
            self::$data['total'] = UserInfoDAL::getTotal($keywords, $this->enterprise_id);

            self::$data['currentPage'] = $currentPage;
            self::$data['pagesize'] = $pagesize;
            self::$data['keywords'] = $keywords;
            self::$data['class'] = $this->class;
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::USER_INDEX], code::USER_INDEX, json_encode($ex));
        }
        \mod\init::getTemplate('admin', $this->class . '_' . __FUNCTION__);
    }

    function getCustomer() {
        Common::isset_cookie();
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        try {
            if ($id != null) {
                $res = UserInfoDAL::getOne($id);
                $resCourse=UserInfoDAL::getUserEnterpriseCourseList($id, $this->enterprise_id);
                $course= CourseDAL::getAll(1, 999, '','', $this->enterprise_id);
                self::$data['data'] = $res;
                self::$data['userCourse'] = $resCourse;
                self::$data['course'] = $course;
            } else {
                self::$data['data'] = null;
            }
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::USER_INDEX], code::USER_INDEX, json_encode($ex));
        }
        \mod\init::getTemplate('admin', $this->class . '_' . __FUNCTION__);
    }

}
