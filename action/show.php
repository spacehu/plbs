<?php

namespace action;

use mod\common as Common;
use mod\upload as Upload;
use TigerDAL;
use TigerDAL\Cms\ImageDAL;
use TigerDAL\Cms\MediaDAL;
use TigerDAL\Cms\ArtMusicDAL;
use TigerDAL\Cms\ArtVideoDAL;
use TigerDAL\Cms\ArtImageDAL;
use TigerDAL\Cms\ArticleDAL;
use config\code;

class show {

    private $class;
    private $showList = ['article', 'notice', 'share'];
    private $mediaList = ['music', 'video'];
    public static $data;

    function __construct() {
        $this->class = str_replace('action\\', '', __CLASS__);
    }

    function staticPage() {
        Common::isset_cookie();

        \mod\init::getTemplate('admin', $this->class . '_' . __FUNCTION__);
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
            self::$data['class'] = $this->class;


            self::$data['data'] = ArticleDAL::getAll($currentPage, $pagesize, $keywords);
            self::$data['total'] = ArticleDAL::getTotal($keywords);

            \mod\init::getTemplate('admin', $this->class . '_' . __FUNCTION__);
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::SHOW_INDEX], code::SHOW_INDEX, json_encode($ex));
        }
    }

    /*     * ************************************************************ */

    function getShow() {
        Common::isset_cookie();
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $type = isset($_GET['type']) ? $_GET['type'] : null;
        self::$data['type'] = $type;
        try {
            if ($id != null) {
                self::$data['data'] = ArticleDAL::getOne($id);
            } else {
                self::$data['data'] = null;
            }
            //Common::pr(self::$data['data']);die;
            self::$data['image'] = ImageDAL::getAll(1, 999, "");
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::SHOW_INDEX], code::SHOW_INDEX, json_encode($ex));
        }
        \mod\init::getTemplate('admin', $this->class . '_' . __FUNCTION__);
    }

    function updateShow() {
        Common::isset_cookie();
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        try {
            if ($id != null) {
                /** 更新操作 */
                $data = [
                    'name' => $_POST['name'],
                    'overview' => isset($_POST['overview']) ? $_POST['overview'] : '',
                    'detail' => isset($_POST['detail']) ? $_POST['detail'] : '',
                    'access' => isset($_POST['access']) ? $_POST['access'] : 0,
                    'source' => isset($_POST['source']) ? $_POST['source'] : '',
                    'media_id' => isset($_POST['media_id']) ? $_POST['media_id'] : 0,
                    'edit_by' => Common::getSession("id"),
                ];
                self::$data = ArticleDAL::update($id, $data);
            } else {
                /** 新增操作 */
                $data = [
                    'name' => $_POST['name'],
                    'overview' => isset($_POST['overview']) ? $_POST['overview'] : '',
                    'detail' => isset($_POST['detail']) ? $_POST['detail'] : '',
                    'cat_id' => 0,
                    'order_by' => 50,
                    'add_by' => Common::getSession("id"),
                    'add_time' => date("Y-m-d H:i:s"),
                    'edit_by' => Common::getSession("id"),
                    'edit_time' => date("Y-m-d H:i:s"),
                    'delete' => 0,
                    'access' => isset($_POST['access']) ? $_POST['access'] : 0,
                    'source' => isset($_POST['source']) ? $_POST['source'] : '',
                    'media_id' => isset($_POST['media_id']) ? $_POST['media_id'] : 0,
                ];
                self::$data = ArticleDAL::insert($data);
            }
            if (self::$data) {
                //Common::pr(Common::getSession($this->class));die;
                Common::js_redir(Common::getSession($this->class));
            } else {
                Common::js_alert('修改失败，请联系系统管理员');
            }
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::SHOW_UPDATE], code::SHOW_UPDATE, json_encode($ex));
        }
    }

    function deleteShow() {
        Common::isset_cookie();
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        try {
            if ($id != null) {
                self::$data = ArticleDAL::delete($id);
            }
            Common::js_redir(Common::getSession($this->class));
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::SHOW_DELETE], code::SHOW_DELETE, json_encode($ex));
        }
    }

}
