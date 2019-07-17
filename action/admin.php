<?php

namespace action;

use mod\common as Common;
use TigerDAL\Cms\UserDAL;
use TigerDAL\Cms\RoleDAL;

class admin {

    public static $data;

    function __construct() {
        
    }

    function index() {
        Common::isset_cookie();
        \mod\init::getTemplate('admin', 'main', false);
    }

    function main_top() {
        Common::isset_cookie();
        $id = Common::getSession("id");
        try {
            self::$data['data'] = UserDAL::getOne($id);
            self::$data['data']['role'] = RoleDAL::getOne(self::$data['data']['role_id']);
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::USER_INDEX], code::USER_INDEX, json_encode($ex));
        }
        \mod\init::getTemplate('admin', 'top', false);
    }

    function main_right() {
        Common::isset_cookie();
        \mod\init::getTemplate('admin', 'right', false);
    }

    function main_left() {
        Common::isset_cookie();
        \mod\init::getTemplate('admin', 'left', false);
    }

    function error() {
        \mod\init::getTemplate('admin', 'error', false);
        exit;
    }

}
