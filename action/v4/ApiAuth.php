<?php

namespace action\v4;

use action\RestfulApi;
use http\Exception;
use mod\common as Common;
use TigerDAL\Api\AuthDAL;
use TigerDAL\Api\TokenDAL;
use TigerDAL\Api\WeChatDAL;
use TigerDAL\Api\LogDAL;
use config\code;
use TigerDAL\CatchDAL;

class ApiAuth extends RestfulApi {

    /**
     * 主方法引入父类的基类
     * 责任是分担路由的工作
     */
    function __construct() {
        $path = parent::__construct();
        if (!empty($path)) {
            $_path = explode("-", $path);
            $mod= $_path['2'];
            $res=$this->$mod();
            exit(json_encode($res));
        }
    }

    /** 检查手机号是否已用 */
    function checkPhone() {
        try {
            $phone = Common::specifyChar($this->post['phone']);
            $code = Common::specifyChar($this->post['code']);
            $AuthDAL = new AuthDAL();
            $check = $AuthDAL->checkPhone($phone, $code);
            if ($check !== true) {
                self::$data['success'] = false;
                self::$data['data']['code'] = $check;
                self::$data['msg'] = code::$code[$check];
                return self::$data;
            }
            self::$data['data'] = $check;
            return self::$data;
        } catch (Exception $ex) {
            CatchDAL::markError(code::$code[code::HOME_INDEX], code::HOME_INDEX, json_encode($ex));
        }
    }

    /** 注册 */
    function register() {
        try {
            $phone = Common::specifyChar($this->post['phone']);
            $code = Common::specifyChar($this->post['code']);
            $name = Common::specifyChar($this->post['name']);
            $password = Common::specifyChar($this->post['password']);
            $cfn_password = Common::specifyChar($this->post['cfn_password']);
            if (strlen($password) < 6) {
                self::$data['success'] = false;
                self::$data['data']['code'] = 'errorPasswordLength';
                self::$data['msg'] = code::$code['errorPasswordLength'];
                return self::$data;
            }
            if ($password != $cfn_password) {
                self::$data['success'] = false;
                self::$data['data']['code'] = 'errorPasswordDifferent';
                self::$data['msg'] = code::$code['errorPasswordDifferent'];
                return self::$data;
            }
            $AuthDAL = new AuthDAL();
            $check = $AuthDAL->checkPhone($phone, $code);
            if ($check !== true) {
                self::$data['success'] = false;
                self::$data['data']['code'] = $check;
                self::$data['msg'] = code::$code[$check];
                return self::$data;
            }
            $data = [
                'name' => $name,
                'phone' => $phone,
                'nickname' => '',
                'photo' => '',
                'brithday' => '',
                'province' => '',
                'city' => '',
                'district' => '',
                'email' => '',
                'sex' => '',
                'add_time' => date("Y-m-d H:i:s", time()),
                'edit_time' => date("Y-m-d H:i:s", time()),
                'user_id' => 0, //弃用字段
                'password' => md5($password),
                'last_login_time' => date("Y-m-d H:i:s", time()),
            ];
            $res = $AuthDAL->insert($data);
            if (!empty($res)) {
                self::$data['data'] = $res;
                if (empty($this->header['openid'])) {
                    $wechat = new WeChatDAL();
                    $openid = $this->header['openid'];
                    $result = $wechat->getOpenId($openid);     //根据OPENID查找数据库中是否有这个用户，如果没有就写数据库。继承该类的其他类，用户都写入了数据库中。  
                    LogDAL::saveLog("DEBUG", "INFO", json_encode($result));
                    if (empty($result)) {
                        $_data = [
                            'openid' => $openid,
                            'nickname' => '',
                            'sex' => '',
                            'language' => '',
                            'city' => '',
                            'province' => '',
                            'country' => '',
                            'headimgurl' => '',
                            'privilege' => '',
                            'add_time' => date("Y-m-d H:i:s"),
                            'edit_time' => date("Y-m-d H:i:s"),
                            'user_id' => $res,
                            'phone' => "",
                        ];
                        LogDAL::saveLog("DEBUG", "INFO", json_encode($_data));
                        $wechat->addWeChatUserInfo($_data);
                    }
                }
            } else {
                self::$data['success'] = false;
                self::$data['data']['code'] = "errorSql";
                self::$data['msg'] = code::$code['errorSql'];
            }
        } catch (Exception $ex) {
            CatchDAL::markError(code::$code[code::HOME_INDEX], code::HOME_INDEX, json_encode($ex));
        }
        return self::$data;
    }

    /** 用户登录 */
    function login() {
        try {
            $phone = Common::specifyChar($this->post['phone']);
            $password = Common::specifyChar($this->post['password']);

            $AuthDAL = new AuthDAL();
            $check = $AuthDAL->checkUser($phone, $password);
            if ($check['error'] == 1) {
                self::$data['success'] = false;
                self::$data['data']['code'] = $check['code'];
                self::$data['msg'] = code::$code[$check['code']];
            } else {
                if (!empty($this->header['openid'])) {
                    $wechat = new WeChatDAL();
                    $openid = $this->header['openid'];
                    $result = $wechat->getOpenId($openid);     //根据OPENID查找数据库中是否有这个用户，如果没有就写数据库。继承该类的其他类，用户都写入了数据库中。  
                    LogDAL::saveLog("DEBUG", "INFO", json_encode($result));
                    $_data = [
                        'user_id' => $check['data']['id'],
                    ];
                    $wechat->updateWeChatUserInfo($result['id'], $_data);
                }
                self::$data['data']['code'] = $check['code'];
                self::$data['data']['token'] = TokenDAL::saveToken($check['data']['id'], \mod\init::$config['token']['server_id']['customer']);
                self::$data['data']['deathline'] = TokenDAL::getTimeOut();
            }
        } catch (Exception $ex) {
            CatchDAL::markError(code::$code[code::HOME_INDEX], code::HOME_INDEX, json_encode($ex));
        }
        return self::$data;
    }

    /** 登出 */
    function logout() {
        try {
            $TokenDAL = new TokenDAL();
            $TokenDAL->delToken();
            if (empty($this->header['openid'])) {
                $wechat = new WeChatDAL();
                $openid = $this->header['openid'];
                $result = $wechat->getOpenId($openid);     //根据OPENID查找数据库中是否有这个用户，如果没有就写数据库。继承该类的其他类，用户都写入了数据库中。  
                LogDAL::saveLog("DEBUG", "INFO", json_encode($result));
                $_data = [
                    'user_id' => '',
                ];
                $wechat->addWeChatUserInfo($result['id'], $_data);
            }
        } catch (Exception $ex) {
            CatchDAL::markError(code::$code[code::HOME_INDEX], code::HOME_INDEX, json_encode($ex));
        }
        return self::$data;
    }

    /** 重置密码 */
    function reset() {
        try {
            $phone = Common::specifyChar($this->post['phone']);
            $code = Common::specifyChar($this->post['code']);
            $password = Common::specifyChar($this->post['password']);
            $cfn_password = Common::specifyChar($this->post['cfn_password']);
            if (strlen($password) < 6) {
                self::$data['success'] = false;
                self::$data['data']['code'] = 'errorPasswordLength';
                self::$data['msg'] = code::$code['errorPasswordLength'];
                return self::$data;
            }
            if ($password != $cfn_password) {
                self::$data['success'] = false;
                self::$data['data']['code'] = 'errorPasswordDifferent';
                self::$data['msg'] = code::$code['errorPasswordDifferent'];
                return self::$data;
            }
            $AuthDAL = new AuthDAL();
            $user = $AuthDAL->getUserInfoByCode($phone, $code);
            if (is_string($user)) {
                self::$data['success'] = false;
                self::$data['data']['code'] = $user;
                self::$data['msg'] = code::$code[$user];
                return self::$data;
            }
            $data = [
                'edit_time' => date("Y-m-d H:i:s", time()),
                'password' => md5($password),
            ];
            $res = $AuthDAL->updateUserInfo($user['id'], $data);
            if (!empty($res)) {
                self::$data['data'] = $res;
            } else {
                self::$data['success'] = false;
                self::$data['data']['code'] = "errorSql";
                self::$data['msg'] = code::$code['errorSql'];
            }
        } catch (Exception $ex) {
            CatchDAL::markError(code::$code[code::HOME_INDEX], code::HOME_INDEX, json_encode($ex));
        }
        return self::$data;
    }

    /** 企业主登录 */
    function sign() {
        try {
            $phone = Common::specifyChar($this->post['phone']);
            $password = Common::specifyChar($this->post['password']);

            $AuthDAL = new AuthDAL();
            $check = $AuthDAL->checkEnterPrise($phone, $password);
            if ($check['error'] == 1) {
                self::$data['success'] = false;
                self::$data['data']['code'] = $check['code'];
                self::$data['msg'] = code::$code[$check['code']];
            } else {
                self::$data['data']['code'] = $check['code'];
                self::$data['data']['token'] = TokenDAL::saveToken($check['data']['id'], \mod\init::$config['token']['server_id']['business']);
                self::$data['data']['deathline'] = TokenDAL::getTimeOut();
            }
        } catch (Exception $ex) {
            CatchDAL::markError(code::$code[code::HOME_INDEX], code::HOME_INDEX, json_encode($ex));
        }
        return self::$data;
    }

}
