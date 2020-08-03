<?php
/**************************************************************************************
 * space                                                                              *
 * 针对问卷系统开发的api                                                                *
 * 可以使用restful处理方式                                                              *
 * 包含逻辑：                                                                          *
 *              获取问卷及状态逻辑                                                      *
 *              完成问卷逻辑                                                           *
 *************************************************************************************/

namespace action\v4;

use action\RestfulApi;
use http\Exception;
use mod\common;
use TigerDAL\Api\EnterpriseDAL;
use TigerDAL\Api\LessonDAL;
use TigerDAL\Api\SignDAL;
use TigerDAL\Api\SignQuestionnaireDAL;
use TigerDAL\Api\UserLessonTimeDAL;
use TigerDAL\CatchDAL;
use TigerDAL\Api\TestDAL;
use TigerDAL\Api\QuestionnaireDAL;
use config\code;

class ApiQuestionnaire extends RestfulApi
{

    public $openid;
    public $user_id;
    public $server_id;
    public $enterprise_id;

    /**
     * 主方法引入父类的基类
     * 责任是分担路由的工作
     */
    function __construct()
    {
        $path = parent::__construct();
        $this->post = Common::exchangePost();
        $this->get = Common::exchangeGet();
        $this->header = Common::exchangeHeader();
        $enterprise = EnterpriseDAL::getByCode($this->get['code']);
        if (!empty($enterprise)) {
            $this->enterprise_id = $enterprise['id'];
        }
        if (!empty($path)) {
            $_path = explode("-", $path);
            $mod = $_path['2'];
            $res = $this->$mod();
            exit(json_encode($res));
        }
    }


    /**
     * 试卷info
     * 获取问卷接口 校验是否参与过问卷 条件需要确认是以签到还是答卷
     *
     */
    function questionnaire()
    {
        // 校验 问卷id
        $questionnaire_id = $this->get['questionnaire_id'];
        if (empty($questionnaire_id)) {
            self::$data['success'] = false;
            self::$data['data']['error_msg'] = 'emptyparameter';
            self::$data['msg'] = code::$code['emptyparameter'];
            return self::$data;
        }else{
            // default signed return
            self::$data['data']['signed']['count'] = 0;
            self::$data['data']['signed']['data'] = null;
            self::$data['data']['signed']['questionnaire'] = null;
        }
        //  是否已经签到 用户openid 问卷id 企业id
        $signed = SignDAL::getSigned($this->openid, $this->enterprise_id, $questionnaire_id);
        if (!empty($signed)) {
            self::$data['data']['signed']['count'] = 1;
            self::$data['data']['signed']['data'] = $signed;
            //  是否参与过问卷 用户id 问卷id 企业id
            $signed_q = SignQuestionnaireDAL::getSignQuestionnaire($signed['id'], $this->enterprise_id, $questionnaire_id);
            if(!empty($signed_q)){
                self::$data['data']['signed']['questionnaire'] = $signed_q;
            }
        }


        try {
            // 获取问卷详细 和 问卷的试题
            $_obj = QuestionnaireDAL::getOne($questionnaire_id);
            $res = TestDAL::getQuestionnaire($questionnaire_id);
            //print_r($res);die;
            self::$data['data']['info'] = $_obj;
            self::$data['data']['list'] = $res;
            self::$data['data']['total'] = count($res);
        } catch (Exception $ex) {
            CatchDAL::markError(code::$code[code::HOME_INDEX], code::HOME_INDEX, json_encode($ex));
        }
        return self::$data;
    }

    /**
     * 执行签到
     * 执行签到操作记录用户参与的 企业与问卷 并且收录 姓名 电话 邮箱 备注 openid
     */
    function saveSign(){
        try {
            $userlesson = LessonDAL::getUserLesson($this->user_id, $this->post['lesson_id']);
            if (empty($userlesson)) {
                self::$data['success'] = false;
                self::$data['data']['error_msg'] = 'emptyuserlessonid';
                self::$data['data']['code'] = $userlesson;
                self::$data['msg'] = "emptyuserlessonid";
                return self::$data;
            }
            $_data = [
                'user_id' => $this->user_id,
                'lesson_id' => $this->post['lesson_id'],
                'user_lesson_id' => $userlesson['id'],
                'add_time' => "NOW()",
                'delete' => !empty($this->post['delete']) ? $this->post['delete'] : 0,
                'duration' => $this->post['duration'], // seconds
            ];
            self::$data['data']['id'] = UserLessonTimeDAL::insert($_data);
        } catch (Exception $ex) {
            CatchDAL::markError(code::$code[code::HOME_INDEX], code::HOME_INDEX, json_encode($ex));
        }
        return self::$data;
    }
    /**
     * 收录问卷答案
     * 记录问卷答案的接口用来对未来的数据进行剖析
     */
    function saveQuestionnaire(){

    }
}
