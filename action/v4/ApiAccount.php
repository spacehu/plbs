<?php

namespace action\v4;

use mod\common as Common;
use TigerDAL\Api\AuthDAL;
use TigerDAL\Api\TokenDAL;
use TigerDAL\Cms\EnterpriseDAL;
use TigerDAL\Api\CourseDAL;
use TigerDAL\Api\TestDAL;
use TigerDAL\Api\AccountDAL;
use config\code;

class ApiAccount extends \action\RestfulApi {

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

    /** 企业||用户 信息 */
    function info() {
        try {
            //轮播列表
            $AuthDAL = new AuthDAL();
            $res;
            switch ($this->server_id) {
                case \mod\init::$config['token']['server_id']['customer']:
                    $res = $AuthDAL->getUserInfo($this->user_id);
                    break;
                case \mod\init::$config['token']['server_id']['business']:
                    $res = EnterpriseDAL::getByUserId($this->user_id);
                    break;
                case \mod\init::$config['token']['server_id']['management']:
                    break;
                default:
                    break;
            }
            self::$data['data']['userType'] = $this->server_id;
            //print_r($res);die;
            self::$data['data']['userInfo'] = $res;
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::HOME_INDEX], code::HOME_INDEX, json_encode($ex));
        }
        return self::$data;
    }

    /** 参与课程 */
    function course() {
        try {
            //轮播列表
            $_data = [
                'user_id' => $this->user_id,
                'course_id' => $this->post['course_id'],
                'status' => 1,
                'add_time' => date("Y-m-d H:i:s"),
                'edit_time' => date("Y-m-d H:i:s"),
                'delete' => 0,
            ];
            $res = CourseDAL::joinCourse($_data);

            //print_r($res);die;
            self::$data['data'] = $res;
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::HOME_INDEX], code::HOME_INDEX, json_encode($ex));
        }
        return self::$data;
    }

    /** 参与考试 */
    function testing() {
        try {
            //轮播列表
            $_data = [
                'user_id' => $this->user_id,
                'course_id' => $this->post['course_id'],
                'aws' => (array) $this->post['aws'],
                'time' => date("Y-m-d H:i:s"),
            ];
            $res = TestDAL::joinTest($_data);

            //print_r($res);die;
            self::$data['data'] = $res;
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::HOME_INDEX], code::HOME_INDEX, json_encode($ex));
        }
        return self::$data;
    }

    /** 收藏 */
    function favorite() {
        try {
            //轮播列表
            $_data = [
                'user_id' => $this->user_id,
                'article_id' => $this->post['article_id'],
                'add_time' => date("Y-m-d H:i:s"),
                'edit_time' => date("Y-m-d H:i:s"),
                'delete' => 0,
            ];
            $res = AccountDAL::addFavorites($_data);

            //print_r($res);die;
            self::$data['data'] = $res;
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::HOME_INDEX], code::HOME_INDEX, json_encode($ex));
        }
        return self::$data;
    }

    /** 参与过的课程列表 */
    function courses() {
        try {
            //轮播列表
            if ($this->server_id != \mod\init::$config['token']['server_id']['customer']) {
                self::$data['success'] = false;
                self::$data['data']['code'] = "errorType";
                self::$data['msg'] = code::$code["errorType"];
                return self::$data;
            }
            $currentPage = isset($this->get['currentPage']) ? $this->get['currentPage'] : 1;
            $pagesize = isset($this->get['pagesize']) ? $this->get['pagesize'] : \mod\init::$config['page_width'];
            $res = AccountDAL::getCourses($currentPage, $pagesize, $this->user_id);

            self::$data['data']['userType'] = $this->server_id;
            //print_r($res);die;
            self::$data['data']['userInfo'] = $res;
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::HOME_INDEX], code::HOME_INDEX, json_encode($ex));
        }
        return self::$data;
    }

}
