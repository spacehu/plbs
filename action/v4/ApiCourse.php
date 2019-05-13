<?php

namespace action\v4;

use mod\common as Common;
use TigerDAL\Api\TokenDAL;
use TigerDAL\Api\CourseDAL;
use TigerDAL\Api\LessonDAL;
use TigerDAL\Cms\LessonImageDAL;
use TigerDAL\Api\TestDAL;
use config\code;

class ApiCourse extends \action\RestfulApi {

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
    function courses() {
        $currentPage = isset($this->get['currentPage']) ? $this->get['currentPage'] : 1;
        $pagesize = isset($this->get['pagesize']) ? $this->get['pagesize'] : \mod\init::$config['page_width'];
        $keywords = isset($this->get['keywords']) ? $this->get['keywords'] : "";
        $cat_id = isset($this->get['cat_id']) ? $this->get['cat_id'] : '';
        try {
            //轮播列表

            $res = CourseDAL::getAll($currentPage, $pagesize, $keywords, $cat_id, 0);
            $total = CourseDAL::getTotal($keywords, $cat_id, 0);

            //print_r($res);die;
            self::$data['data']['list'] = $res;
            self::$data['data']['total'] = $total;
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::HOME_INDEX], code::HOME_INDEX, json_encode($ex));
        }
        return self::$data;
    }

    /** 课程 信息 */
    function course() {
        if (empty($this->get['course_id'])) {
            self::$data['success'] = false;
            self::$data['data']['error_msg'] = 'emptyparameter';
            self::$data['msg'] = code::$code['emptyparameter'];
            return self::$data;
        }
        try {
            //轮播列表
            $res = CourseDAL::getOne($this->get['course_id'],$this->user_id);
            //print_r($res);die;
            self::$data['data'] = $res;
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::HOME_INDEX], code::HOME_INDEX, json_encode($ex));
        }
        return self::$data;
    }

    /** 课时 信息 */
    function lessons() {
        if (empty($this->get['course_id'])) {
            self::$data['success'] = false;
            self::$data['data']['error_msg'] = 'emptyparameter';
            self::$data['msg'] = code::$code['emptyparameter'];
            return self::$data;
        }
        $currentPage = isset($this->get['currentPage']) ? $this->get['currentPage'] : 1;
        $pagesize = isset($this->get['pagesize']) ? $this->get['pagesize'] : \mod\init::$config['page_width'];
        $keywords = isset($this->get['keywords']) ? $this->get['keywords'] : "";
        $course_id = $this->get['course_id'];
        try {
            //轮播列表

            $res = LessonDAL::getAll($currentPage, $pagesize, $keywords, $course_id);
            $total = LessonDAL::getTotal($keywords, $course_id);

            //print_r($res);die;
            self::$data['data']['list'] = $res;
            self::$data['data']['total'] = $total;
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::HOME_INDEX], code::HOME_INDEX, json_encode($ex));
        }
        return self::$data;
    }

    /** 课时 信息 */
    function lesson() {
        if (empty($this->get['lesson_id'])) {
            self::$data['success'] = false;
            self::$data['data']['error_msg'] = 'emptyparameter';
            self::$data['msg'] = code::$code['emptyparameter'];
            return self::$data;
        }
        try {
            $res = LessonDAL::getOne($this->get['lesson_id']);
            //轮播列表
            $images = LessonImageDAL::getImageList($this->get['lesson_id']);
            //print_r($res);die;
            self::$data['data'] = $res;
            self::$data['data']['images'] = $images;
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::HOME_INDEX], code::HOME_INDEX, json_encode($ex));
        }
        return self::$data;
    }

    /** 试题 信息 */
    function tests() {
        if (empty($this->get['course_id'])) {
            self::$data['success'] = false;
            self::$data['data']['error_msg'] = 'emptyparameter';
            self::$data['msg'] = code::$code['emptyparameter'];
            return self::$data;
        }
        try {
            //轮播列表
            $TestDAL = new TestDAL();
            $CourseDAL = new CourseDAL();
            $_obj = $CourseDAL->getOne($this->get['course_id']);
            $res = $TestDAL->getRand($this->get['course_id'], $_obj['text_max']);
            //print_r($res);die;
            self::$data['data']['list'] = $res;
            self::$data['data']['total'] = count($res);
            self::$data['data']['max'] = $_obj['text_max'];
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::HOME_INDEX], code::HOME_INDEX, json_encode($ex));
        }
        return self::$data;
    }

}
