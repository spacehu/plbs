<?php

namespace action\v4;

use mod\common as Common;
use TigerDAL\Api\TokenDAL;
use TigerDAL\Api\ArticleDAL;
use TigerDAL\Api\AccountDAL;
use TigerDAL\Api\ResumeDAL;;
use TigerDAL\Api\ExaminationDAL;
use TigerDAL\Api\ExamDAL;
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
        $this->user_id = 0;
        $this->server_id = 0;
        if (!empty($path)) {
            $_path = explode("-", $path);
            $actEval = "\$res = \$this ->" . $_path['2'] . "();";
            eval($actEval);
            exit(json_encode($res));
        }
    }

    /** 课程 信息 */
    function supports() {
        if ((!empty($this->header['token']))) {
            $_base = TokenDAL::reToken(self::$data);
            $this->user_id = $_base['user_id'];
            $this->server_id = $_base['server_id'];
        }
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

            if ($cat_id == '17') {
                if (!empty($res)) {
                    foreach ($res as $k => $v) {
                        $_row = ResumeDAL::getResumeArticle($this->user_id, $v['id']);
                        $res[$k]['resume_article'] = (!empty($_row)) ? ($_row['delete'] == 0) ? 1 : 0 : 0;
                    }
                }
            }
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
        $_base = TokenDAL::reToken(self::$data);
        $this->user_id = $_base['user_id'];
        $this->server_id = $_base['server_id'];
        if (empty($this->get['article_id'])) {
            self::$data['success'] = false;
            self::$data['data']['error_msg'] = 'emptyparameter';
            self::$data['msg'] = code::$code['emptyparameter'];
            return self::$data;
        }
        try {
            //轮播列表
            $res = ArticleDAL::getOne($this->get['article_id']);
            if(!empty($res)&&!empty($res['examination_id'])){
                $examination=ExaminationDAL::getOne($res['examination_id']);
                if(!empty($examination)){
                    $exam=ExamDAL::getByExaminationId($res['examination_id']);
                    //var_dump($exam);die;
                    if($examination['percentage']>$exam['point']){
                        $is_pass_exam=[
                            "percentage"=>$examination['percentage'],
                            "point"=>$exam['point'],
                            "value"=>false,
                        ];
                    }else{
                        $is_pass_exam=[
                            "percentage"=>$examination['percentage'],
                            "point"=>$exam['point'],
                            "value"=>true,
                        ];
                    }
                }
            }
            $resF = AccountDAL::getFavorite($this->user_id, $this->get['article_id']);
            $resRA = ResumeDAL::getResumeArticle($this->user_id, $this->get['article_id']);
            self::$data['data'] = $res;
            self::$data['data']['favorites'] = (!empty($resF)) ? ($resF['delete'] == 0) ? 1 : 0 : 0;
            self::$data['data']['resume_article'] = (!empty($resRA)) ? ($resRA['delete'] == 0) ? 1 : 0 : 0;
            self::$data['data']['is_pass_exam']=$is_pass_exam;
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::HOME_INDEX], code::HOME_INDEX, json_encode($ex));
        }
        return self::$data;
    }

}
