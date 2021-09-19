<?php

namespace action;

use http\Exception;
use mod\common as Common;
use mod\init;
use TigerDAL;
use TigerDAL\CatchDAL;
use TigerDAL\Cms\UserInfoLessonTimeDAL;
use TigerDAL\Cms\EnterpriseUserDAL;
use config\code;

class user {

    private $class;
    public static $data;

    function __construct() {
        $this->class = str_replace('action\\', '', __CLASS__);
    }

    function plusUserLessonTime() {
        Common::isset_cookie();
        $enterpriseid = isset($_GET['enterpriseid']) ? $_GET['enterpriseid'] : null;
        $time = isset($_GET['time']) ? $_GET['time'] : null;
        $ignoreHistory = isset($_GET['ignore']) ? $_GET['ignore'] : null;
        try {
            if(empty($enterpriseid)){
                init::getTemplate('admin', $this->class . '_' . __FUNCTION__);
                return;
            }
            // get users
            $users=EnterpriseUserDAL::getUserIdByEnterpriseId($enterpriseid);
            if(!empty($users)){
                foreach($users as $k=>$v){
                    // add times
                    // todo 需要追加数据库字段status 0 常规 1 bonus。ignore需要确认输入状态。
                    $userlesson=UserInfoLessonTimeDAL::getOneByUserId($v['user_id']);
                    if(empty($userlesson)){ 
                        continue;
                    }else if($ignoreHistory&&$userlesson['status']==1){
                        continue;
                    }
                    $data=[
                        "user_id"=>$v['user_id'],
                        "lesson_id"=>$userlesson['lesson_id'],
                        "user_lesson_id"=>$userlesson['user_lesson_id'],
                        "add_time"=>"NOW()",
                        "delete"=>0,
                        "duration"=>$time,
                    ];
                    UserInfoLessonTimeDAL::insertUserLesson($data);
                }
            }

        } catch (Exception $ex) {
            CatchDAL::markError(code::$code[code::USER_INDEX], code::USER_INDEX, json_encode($ex));
        }
        init::getTemplate('admin', $this->class . '_' . __FUNCTION__);
    }


}
