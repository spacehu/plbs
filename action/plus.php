<?php

namespace action;

use http\Exception;
use mod\common as Common;
use mod\init;
use TigerDAL;
use TigerDAL\CatchDAL;
use TigerDAL\Cms\UserInfoLessonTimeDAL;
use TigerDAL\Cms\EnterpriseUserDAL;
use TigerDAL\Cms\EnterpriseDAL;
use config\code;

class plus {

    private $class;
    public static $data;

    function __construct() {
        $this->class = str_replace('action\\', '', __CLASS__);
    }

    function plusUserLessonTime() {
        Common::isset_cookie();
        $enterpriseid = isset($_POST['enterpriseid']) ? $_POST['enterpriseid'] : null;
        $time = isset($_POST['time']) ? $_POST['time'] : null;
        $ignoreHistory = isset($_POST['ignore']) ? $_POST['ignore'] : null;
        try {
            self::$data['enterpriseList']=EnterpriseDAL::getAll(1,999);
            self::$data['data']=[
                'enterpriseid'=>$enterpriseid,
                'time'=>$time,
                'ignore'=>$ignoreHistory,
                'msg'=>'这里的操作将会对学员已经参与的课时进行奖励。'
            ];
            if(empty($enterpriseid)){
                init::getTemplate('admin', $this->class . '_' . __FUNCTION__);
                return;
            }
            // get users
            $users=EnterpriseUserDAL::getUserIdByEnterpriseId($enterpriseid);
            if(!empty($users)){
                $_users=0;
                foreach($users as $k=>$v){
                    // add times
                    // todo 需要追加数据库字段status 0 常规 1 bonus。ignore需要确认输入状态。
                    $userlesson=UserInfoLessonTimeDAL::getOneByUserId($v['user_id']);
                    if(empty($userlesson)){ 
                        continue;
                    }else if($ignoreHistory=='on'&&$userlesson['status']==1){
                        continue;
                    }
                    $data=[
                        "user_id"=>$v['user_id'],
                        "lesson_id"=>$userlesson['lesson_id'],
                        "user_lesson_id"=>$userlesson['user_lesson_id'],
                        "add_time"=>"NOW()",
                        "delete"=>0,
                        "duration"=>$time*3600,
                        "status"=>1,
                    ];
                    UserInfoLessonTimeDAL::insertUserLessonTime($data);
                    $_users++;
                }
                self::$data['data']['msg']='奖励已经下发。奖励时间'.$time.'小时。共'.$_users.'位学员被奖励。';
            }else{
                self::$data['data']['msg']='没有可以下发的学员。';
            }

        } catch (Exception $ex) {
            CatchDAL::markError(code::$code[code::USER_INDEX], code::USER_INDEX, json_encode($ex));
        }
        init::getTemplate('admin', $this->class . '_' . __FUNCTION__);
    }


}
