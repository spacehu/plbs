<?php

namespace action\cli;

use mod\common as Common;
use TigerDAL\cli\UserDAL;
use TigerDAL\MailDAL;
use TigerDAL\cli\LogDAL;
use config\code;

class base {

    /**
     * 主方法引入父类的基类
     * 责任是分担路由的工作
     */
    function __construct() {
        LogDAL::save(date("Y-m-d H:i:s") . "-start", "cli");
    }

    function __destruct() {
        LogDAL::save(date("Y-m-d H:i:s") . "-end", "cli");
        LogDAL::_saveLog();
    }

    /** 向企业管理员发送邮件的方法 */
    function userEmail() {
        try {
            $_userList = UserDAL::getAll();
            if (!empty($_userList)) {
                $_mail = new MailDAL();
                $fromInfo = UserDAL::getConfig();
                foreach ($_userList as $k => $v) {
                    // 根据userid拉取数据
                    //$maildetail = UserDAL::getData($v['id']);
                    $maildetail = [
                        "subject" => "Dear " . $v['name'] . ". ",
                        "body" => $v['mail_content'],
                        "user_email" => $v['email'],
                        "user_name" => $v['name'],
                    ];
                    $_mail->mailTo($fromInfo, $maildetail);
                }
            }
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::HOME_INDEX], code::HOME_INDEX, json_encode($ex));
        }
    }

}
