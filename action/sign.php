<?php

namespace action;

use http\Exception;
use mod\common as Common;
use mod\init;
use TigerDAL\CatchDAL;
use TigerDAL\Cms\EnterpriseDAL;
use TigerDAL\Cms\ImageDAL;
use TigerDAL\Cms\SchoolDAL;
use TigerDAL\Cms\ArticleImageDAL;
use TigerDAL\Cms\ArticleSchoolDAL;
use TigerDAL\Cms\ArticleDAL;
use TigerDAL\Cms\SignDAL;
use TigerDAL\Cms\SystemDAL;
use TigerDAL\Cms\CategoryDAL;
use TigerDAL\Cms\BrandDAL;
use config\code;

class sign {

    private $class;
    public static $data;
    private $enterprise_id;

    function __construct() {
        $this->class = str_replace('action\\', '', __CLASS__);
        //企业id
        try {
            $_enterprise = EnterpriseDAL::getByUserId(Common::getSession("id"));
            if (!empty($_enterprise)) {
                $this->enterprise_id = $_enterprise['id'];
            } else {
                $this->enterprise_id = '';
            }
        } catch (Exception $ex) {
            CatchDAL::markError(code::$code[code::CATEGORY_INDEX], code::CATEGORY_INDEX, json_encode($ex));
        }
    }

    function staticPage() {
        Common::isset_cookie();
        init::getTemplate('admin', $this->class . '_' . __FUNCTION__);
    }

    /**
     *
     */
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
            self::$data['class'] = $this->class;

            self::$data['data'] = SignDAL::getAll($currentPage, $pagesize, $keywords, $this->enterprise_id);
            self::$data['total'] = SignDAL::getTotal($keywords, $this->enterprise_id);

            init::getTemplate('admin', $this->class . '_' . __FUNCTION__);
        } catch (Exception $ex) {
            CatchDAL::markError(code::$code[code::WORKS_INDEX], code::WORKS_INDEX, json_encode($ex));
        }
    }

    function getSign() {
        Common::isset_cookie();
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        try {
            if ($id != null) {
                self::$data['data'] = SignDAL::getOne($id);
            } else {
                self::$data['data'] = null;
            }
            self::$data['class'] = $this->class;
        } catch (Exception $ex) {
            CatchDAL::markError(code::$code[code::WORKS_INDEX], code::WORKS_INDEX, json_encode($ex));
        }
        init::getTemplate('admin', $this->class . '_' . __FUNCTION__);
    }

    function deleteSign() {
        Common::isset_cookie();
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        try {
            if ($id != null) {
                $data=['status'=>0];
                self::$data = SignDAL::update($id,$data);
            }
            Common::js_redir(Common::getSession($this->class));
        } catch (Exception $ex) {
            CatchDAL::markError(code::$code[code::SHOW_DELETE], code::SHOW_DELETE, json_encode($ex));
        }
    }



}
