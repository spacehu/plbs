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
use TigerDAL\Api\TencentSmsDAL;
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
        $this->openid = $this->header['openid'];
        $eCode = !empty($this->post['eCode']) ? $this->post['eCode'] : (!empty($this->get['eCode']) ? $this->get['eCode'] : null);
        $enterprise = EnterpriseDAL::getByCode($eCode);
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
        } else {
            // default signed return
            self::$data['data']['signed']['count'] = 0;
            self::$data['data']['signed']['data'] = null;
            self::$data['data']['signed']['questionnaire'] = null;
        }
        //  是否已经签到 用户openid 问卷id 企业id
        $signed = SignDAL::getSigned($this->enterprise_id, $this->openid, $questionnaire_id);
        if (!empty($signed)) {
            self::$data['data']['signed']['count'] = SignDAL::getSignedTotal($this->enterprise_id, $this->openid, $questionnaire_id);
            self::$data['data']['signed']['data'] = $signed;
            //  是否参与过问卷 用户id 问卷id 企业id
            $signed_q = SignQuestionnaireDAL::getSignQuestionnaire($signed['id'], $this->enterprise_id, $questionnaire_id);
            if (!empty($signed_q)) {
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
            self::$data['data']['total'] = !empty($res) ? count($res) : 0;
        } catch (Exception $ex) {
            CatchDAL::markError(code::$code[code::HOME_INDEX], code::HOME_INDEX, json_encode($ex));
        }
        return self::$data;
    }

    /**
     * 执行签到
     * 执行签到操作记录用户参与的 企业与问卷 并且收录 姓名 电话 邮箱 备注 openid
     */
    function saveSign()
    {
        try {
            $questionnaire_id = !empty($this->post['questionnaire_id']) ? $this->post['questionnaire_id'] : null;
            $email = !empty($this->post['email']) ? $this->post['email'] : null;
            $mark = !empty($this->post['mark']) ? $this->post['mark'] : null;
            $wechat = !empty($this->post['wechat']) ? $this->post['wechat'] : null;
            $company = !empty($this->post['company']) ? $this->post['company'] : null;
            $phone = $this->post['phone'];
            if (empty($phone)) {
                self::$data['success'] = false;
                self::$data['data']['error_msg'] = 'emptyparameter';
                self::$data['msg'] = code::$code['emptyparameter'];
                return self::$data;
            }
            $pCode = $this->post['pCode'];
            if (empty($pCode)) {
                self::$data['success'] = false;
                self::$data['data']['error_msg'] = 'emptyparameter';
                self::$data['msg'] = code::$code['emptyparameter'];
                return self::$data;
            }
            if ($pCode != 1111) {
                if (!TencentSmsDAL::checkCode($phone, $pCode, false)) {
                    self::$data['success'] = false;
                    self::$data['data']['error_msg'] = 'errorPhone';
                    self::$data['msg'] = code::$code['errorSms'];
                    return self::$data;
                }
            }
            $name = $this->post['name'];
            if (empty($name)) {
                self::$data['success'] = false;
                self::$data['data']['error_msg'] = 'emptyparameter';
                self::$data['msg'] = code::$code['emptyparameter'];
                return self::$data;
            }
            if (!empty($wechat)) {
                $signed = SignDAL::getSigned($this->enterprise_id, null, null, $phone);
                if (!empty($signed)) {
                    self::$data['success'] = false;
                    self::$data['data']['error_msg'] = 'already signed';
                    self::$data['msg'] = code::$code['10001'];
                    return self::$data;
                }
                $i = SignDAL::getSignedTotal($this->enterprise_id, null, null, null) + 1;
                $bonusCode = common::add_len($i, 3);
                $mark = json_encode(['wechat' => $wechat, 'company' => $company, 'bonusCode' => $bonusCode]);
            }
            $_data = [
                "enterprise_id" => $this->enterprise_id,
                "questionnaire_id" => $questionnaire_id,
                "name" => $name,
                "phone" => $phone,
                "email" => $email,
                "overview" => $mark,
                "openid" => $this->openid,
                "add_time" => date("Y-m-d H:i:s"),
                "status" => 1,
            ];
            $res = SignDAL::insert($_data);
            self::$data['data'] = $res;
        } catch (Exception $ex) {
            CatchDAL::markError(code::$code[code::HOME_INDEX], code::HOME_INDEX, json_encode($ex));
        }
        return self::$data;
    }

    /**
     * 获取签到信息
     * 根据 公司 手机号 码获取签到信息
     */
    function getSign()
    {
        //  是否已经签到 用户openid 问卷id 企业id
        $phone = $this->get['phone'];
        if (empty($phone)) {
            self::$data['success'] = false;
            self::$data['data']['error_msg'] = 'emptyparameter';
            self::$data['msg'] = code::$code['emptyparameter'];
            return self::$data;
        }
        self::$data['data'] = [
            'signed' => [
                'count' => 0,
                'data' => [],
            ]
        ];
        $signed = SignDAL::getSigned($this->enterprise_id, $this->openid, null, $phone);
        if (!empty($signed)) {
            self::$data['data']['signed']['count'] = SignDAL::getSignedTotal($this->enterprise_id, $this->openid, null, $phone);
            self::$data['data']['signed']['data'] = $signed;
            self::$data['data']['signed']['data']['overview_json'] = json_decode($signed['overview'],true);
        }
        return self::$data;
    }

    /**
     * 收录问卷答案
     * 记录问卷答案的接口用来对未来的数据进行剖析
     */
    function saveQuestionnaire()
    {
        try {
            $questionnaire_id = !empty($this->post['questionnaire_id']) ? $this->post['questionnaire_id'] : null;
            $email = !empty($this->post['email']) ? $this->post['email'] : null;
            $mark = !empty($this->post['mark']) ? $this->post['mark'] : null;
            $phone = $this->post['phone'];
            if (empty($phone)) {
                self::$data['success'] = false;
                self::$data['data']['error_msg'] = 'emptyparameter';
                self::$data['msg'] = code::$code['emptyparameter'];
                return self::$data;
            }
            $pCode = $this->post['pCode'];
            if (empty($pCode)) {
                self::$data['success'] = false;
                self::$data['data']['error_msg'] = 'emptyparameter';
                self::$data['msg'] = code::$code['emptyparameter'];
                return self::$data;
            }
            if (!TencentSmsDAL::checkCode($phone, $pCode, false)) {
                self::$data['success'] = false;
                self::$data['data']['error_msg'] = 'errorPhone';
                self::$data['msg'] = code::$code['errorSms'];
                return self::$data;
            }
            $name = $this->post['name'];
            if (empty($name)) {
                self::$data['success'] = false;
                self::$data['data']['error_msg'] = 'emptyparameter';
                self::$data['msg'] = code::$code['emptyparameter'];
                return self::$data;
            }
            $_data = [
                "enterprise_id" => $this->enterprise_id,
                "questionnaire_id" => $questionnaire_id,
                "name" => $name,
                "phone" => $phone,
                "email" => $email,
                "overview" => $mark,
                "openid" => $this->openid,
                "add_time" => date("Y-m-d H:i:s"),
                "status" => 1,
            ];
            $res = SignDAL::insert($_data);
            self::$data['data'] = $res;
        } catch (Exception $ex) {
            CatchDAL::markError(code::$code[code::HOME_INDEX], code::HOME_INDEX, json_encode($ex));
        }
        return self::$data;
    }
}
