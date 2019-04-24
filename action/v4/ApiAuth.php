<?php

namespace action\v4;

use mod\common as Common;
use TigerDAL\Api\AuthDAL;
use TigerDAL\Api\TokenDAL;
use config\code;

class ApiAuth extends \action\RestfulApi {

    /**
     * 主方法引入父类的基类
     * 责任是分担路由的工作
     */
    function __construct() {
        $path = parent::__construct();
        if (!empty($path)) {
            $_path = explode("-", $path);
            $actEval = "\$res = \$this ->" . $_path['2'] . "();";
            eval($actEval);
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
                return self::$data;
            }
            self::$data['data'] = $check;
            return self::$data;
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::HOME_INDEX], code::HOME_INDEX, json_encode($ex));
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
                return self::$data;
            }
            if ($password != $cfn_password) {
                self::$data['success'] = false;
                self::$data['data']['code'] = 'errorPasswordDifferent';
                return self::$data;
            }
            $AuthDAL = new AuthDAL();
            $check = $AuthDAL->checkPhone($phone, $code);
            if ($check !== true) {
                self::$data['success'] = false;
                self::$data['data']['code'] = $check;
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
                'user_id' => 0,
                'password' => md5($password),
            ];
            $res = $AuthDAL->insert($data);
            if (!empty($res)) {
                self::$data['data'] = $res;
            } else {
                self::$data['success'] = false;
                self::$data['data']['code'] = "errorSql";
            }
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::HOME_INDEX], code::HOME_INDEX, json_encode($ex));
        }
        return self::$data;
    }

    /** 用户登录 */
    function login() {
        try {
            $phone = Common::specifyChar($this->post['phone']);
            $password = Common::specifyChar($this->post['password']);

            $AuthDAL = new AuthDAL();
            $TokenDAL = new TokenDAL();
            $check = $AuthDAL->checkUser($phone, $password);
            if ($check['error'] == 1) {
                self::$data['success'] = false;
                self::$data['data']['code'] = $check['code'];
            } else {
                self::$data['data']['code'] = $check['code'];
                self::$data['data']['token'] = $TokenDAL->saveToken($check['data']['id'], \mod\init::$config['token']['server_id']['customer']);
            }
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::HOME_INDEX], code::HOME_INDEX, json_encode($ex));
        }
        return self::$data;
    }

    /** 登出 */
    function logout() {
        try {
            $TokenDAL = new TokenDAL();
            $TokenDAL->delToken();
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::HOME_INDEX], code::HOME_INDEX, json_encode($ex));
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
                return self::$data;
            }
            if ($password != $cfn_password) {
                self::$data['success'] = false;
                self::$data['data']['code'] = 'errorPasswordDifferent';
                return self::$data;
            }
            $AuthDAL = new AuthDAL();
            $user = $AuthDAL->getUserInfoByCode($phone, $code);
            if (is_string($user)) {
                self::$data['success'] = false;
                self::$data['data']['code'] = $user;
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
            }
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::HOME_INDEX], code::HOME_INDEX, json_encode($ex));
        }
        return self::$data;
    }

    /** 企业主登录 */
    function sign() {
        try {
            $phone = Common::specifyChar($this->post['phone']);
            $password = Common::specifyChar($this->post['password']);

            $AuthDAL = new AuthDAL();
            $TokenDAL = new TokenDAL();
            $check = $AuthDAL->checkEnterPrise($phone, $password);
            if ($check['error'] == 1) {
                self::$data['success'] = false;
                self::$data['data']['code'] = $check['code'];
            } else {
                self::$data['data']['code'] = $check['code'];
                self::$data['data']['token'] = $TokenDAL->saveToken($check['data']['id'], \mod\init::$config['token']['server_id']['business']);
            }
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::HOME_INDEX], code::HOME_INDEX, json_encode($ex));
        }
        return self::$data;
    }

}
