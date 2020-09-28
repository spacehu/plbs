<?php

namespace TigerDAL\Api;

use TigerDAL\BaseDAL;

class SignDAL {

    /** 获取用户信息 */
    public static function getOne($id) {
        $base = new BaseDAL();
        $sql = "select * from " . $base->table_name("sign") . " where id=" . $id . "  limit 1 ;";
        return $base->getFetchRow($sql);
    }

    public static function getSigned($enterprise_id,$openid=null,$questionnaire_id=null,$phone=null) {
        $base = new BaseDAL();
        $where="";
        if(!empty($openid)){
            $where.=" and openid=" . $openid . " ";
        }
        if(!empty($questionnaire_id)){
            $where.=" and questionnaire_id=" . $questionnaire_id . " ";
        }
        if(!empty($phone)){
            $where.=" and phone=" . $phone . " ";
        }
        $sql = "select * from " . $base->table_name("sign") . " where enterprise_id=" . $enterprise_id . $where ." limit 1 ;";
        //echo $sql;die;
        return $base->getFetchRow($sql);
    }
    public static function getSignedTotal($enterprise_id,$openid=null,$questionnaire_id=null,$phone=null) {
        $base = new BaseDAL();
        $where="";
        if(!empty($openid)){
            $where.=" and openid=" . $openid . " ";
        }
        if(!empty($questionnaire_id)){
            $where.=" and questionnaire_id=" . $questionnaire_id . " ";
        }
        if(!empty($phone)){
            $where.=" and phone=" . $phone . " ";
        }
        $sql = "select count(1) as total from " . $base->table_name("sign") . " where enterprise_id=" . $enterprise_id . $where . " limit 1 ;";
        //echo $sql;die;
        return $base->getFetchRow($sql)['total'];
    }
    /** 获取用户信息 */
    public static function getByOpenId($openid) {
        $base = new BaseDAL();
        $sql = "select * from " . $base->table_name("sign") . " where `delete`=0 and openid='" . $openid . "'  limit 1 ;";
        return $base->getFetchRow($sql);
    }

    /** 插入 */
    public static function insert($data) {
        $base = new BaseDAL();
        $base->insert($data, "sign");
        return $base->last_insert_id();
    }


    /** 更新用户信息 */
    public static function update($id, $data) {
        $base = new BaseDAL();
        return $base->update($id,$data,"sign");
    }

}
