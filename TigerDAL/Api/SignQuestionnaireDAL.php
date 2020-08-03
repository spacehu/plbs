<?php

namespace TigerDAL\Api;

use TigerDAL\BaseDAL;

class SignQuestionnaireDAL {

    /** 获取用户信息 */
    public static function getOne($id) {
        $base = new BaseDAL();
        $sql = "select * from " . $base->table_name("sign_questionnaire") . " where id=" . $id . "  limit 1 ;";
        return $base->getFetchRow($sql);
    }

    public static function getSignQuestionnaire($sign_id,$enterprise_id,$questionnaire_id) {
        $base = new BaseDAL();
        $sql = "select * from " . $base->table_name("sign_questionnaire") . " where sign_id=" . $sign_id . " and enterprise_id=" . $enterprise_id . " and questionnaire_id=" . $questionnaire_id . "  limit 1 ;";
        return $base->getFetchRow($sql);
    }
    /** 获取用户信息 */
    public static function getByOpenId($openid) {
        $base = new BaseDAL();
        $sql = "select * from " . $base->table_name("sign_questionnaire") . " where `delete`=0 and openid='" . $openid . "'  limit 1 ;";
        return $base->getFetchRow($sql);
    }

    /** 插入 */
    public static function insert($data) {
        $base = new BaseDAL();
        $base->insert($data, "sign_questionnaire");
        return $base->last_insert_id();
    }


    /** 更新用户信息 */
    public static function update($id, $data) {
        $base = new BaseDAL();
        return $base->update($id,$data,"sign_questionnaire");
    }

}
