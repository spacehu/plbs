<?php

namespace action\v4;

use mod\common as Common;
use TigerDAL\Api\TokenDAL;
use TigerDAL\Api\ArticleDAL;
use TigerDAL\Api\AccountDAL;
use config\code;

class ApiArticle extends \action\RestfulApi {

    public $user_id;
    public $server_id;

    /**
     * 主方法引入父类的基类
     * 责任是分担路由的工作
     */
    function __construct() {
        $path = parent::__construct();
        $this->post = Common::exchangePost();
        $this->get = Common::exchangeGet();
        $this->header = Common::exchangeHeader();
        $TokenDAL = new TokenDAL();
        $_token = $TokenDAL->checkToken();
        //Common::pr($_token);die;
        if ($_token['code'] != 90001) {
            self::$data['success'] = false;
            self::$data['data']['error_msg'] = 'tokenerror';
            self::$data['data']['code'] = $_token['code'];
            self::$data['msg'] = code::$code['tokenerror'];
            exit(json_encode(self::$data));
        }
        $this->user_id = $_token['data']['user_id'];
        $this->server_id = $_token['data']['server_id'];
        if (!empty($path)) {
            $_path = explode("-", $path);
            $actEval = "\$res = \$this ->" . $_path['2'] . "();";
            eval($actEval);
            exit(json_encode($res));
        }
    }

    /** 课程 信息 */
    function supports() {
        $currentPage = isset($this->get['currentPage']) ? $this->get['currentPage'] : 1;
        $pagesize = isset($this->get['pagesize']) ? $this->get['pagesize'] : \mod\init::$config['page_width'];
        $keywords = isset($this->get['keywords']) ? $this->get['keywords'] : "";
        $cat_id = isset($this->get['cat_id']) ? $this->get['cat_id'] : '';
        $enterprise_id = isset($this->get['enterprise_id']) ? $this->get['enterprise_id'] : '';
        $type = isset($this->get['type']) ? $this->get['type'] : '';
        try {
            //轮播列表
            $res = ArticleDAL::getAll($currentPage, $pagesize, $keywords, $cat_id, $enterprise_id, $type);
            $total = ArticleDAL::getTotal($keywords, $cat_id, $enterprise_id, $type);

            //print_r($res);die;
            self::$data['data']['list'] = $res;
            self::$data['data']['total'] = $total;
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::HOME_INDEX], code::HOME_INDEX, json_encode($ex));
        }
        return self::$data;
    }

    /** 课程 信息 */
    function support() {
        if (empty($this->get['article_id'])) {
            self::$data['success'] = false;
            self::$data['data']['error_msg'] = 'emptyparameter';
            self::$data['msg'] = code::$code['emptyparameter'];
            return self::$data;
        }
        try {
            //轮播列表
            $res = ArticleDAL::getOne($this->get['article_id']);
            $resF = AccountDAL::getFavorite($this->user_id, $this->get['article_id']);
            self::$data['data'] = $res;
            self::$data['data']['favorites'] = (!empty($resF)) ? ($resF['delete'] == 0) ? 1 : 0 : 0;
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::HOME_INDEX], code::HOME_INDEX, json_encode($ex));
        }
        return self::$data;
    }

}
