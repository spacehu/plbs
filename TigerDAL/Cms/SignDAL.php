<?php

namespace TigerDAL\Cms;

use TigerDAL\BaseDAL;

class SignDAL
{


    public static function getAll($currentPage, $pagesize, $keywords, $enterprise_id, $openid = null, $questionnaire_id = null, $phone = null)
    {
        $base = new BaseDAL();
        $limit_start = ($currentPage - 1) * $pagesize;
        $limit_end = $pagesize;
        $where = "";
        if (!empty($keywords)) {
            $where .= " and (name like '%" . $keywords . "%' or overview like '%" . $keywords . "%' )";
        }
        if (!empty($openid)) {
            $where .= " and openid=" . $openid . " ";
        }
        if (!empty($questionnaire_id)) {
            $where .= " and questionnaire_id=" . $questionnaire_id . " ";
        }
        if (!empty($phone)) {
            $where .= " and phone=" . $phone . " ";
        }
        if (!empty($enterprise_id)) {
            $where .= " and enterprise_id=" . $enterprise_id . " ";
        }
        $sql = "select * from " . $base->table_name("sign") . " where `status`=1 " . $where . " limit " . $limit_start . "," . $limit_end . " ;";
        //echo $sql;die;
        return $base->getFetchAll($sql);
    }

    public static function getTotal($keywords, $enterprise_id, $openid = null, $questionnaire_id = null, $phone = null)
    {
        $base = new BaseDAL();
        $where = "";
        if (!empty($keywords)) {
            $where .= " and name like '%" . $keywords . "%' ";
        }
        if (!empty($openid)) {
            $where .= " and openid=" . $openid . " ";
        }
        if (!empty($questionnaire_id)) {
            $where .= " and questionnaire_id=" . $questionnaire_id . " ";
        }
        if (!empty($phone)) {
            $where .= " and phone=" . $phone . " ";
        }
        if (!empty($enterprise_id)) {
            $where .= " and enterprise_id=" . $enterprise_id . " ";
        }
        $sql = "select count(1) as total from " . $base->table_name("sign") . " where `status`=1 " . $where . " limit 1 ;";
        //echo $sql;die;
        return $base->getFetchRow($sql)['total'];
    }

    /** 获取用户信息 */
    public static function getOne($id)
    {
        $base = new BaseDAL();
        $sql = "select * from " . $base->table_name("sign") . " where id=" . $id . "  limit 1 ;";
        return $base->getFetchRow($sql);
    }

    /** 获取用户信息 */
    public static function getByOpenId($openid)
    {
        $base = new BaseDAL();
        $sql = "select * from " . $base->table_name("sign") . " where openid='" . $openid . "'  limit 1 ;";
        return $base->getFetchRow($sql);
    }

    /** 插入 */
    public static function insert($data)
    {
        $base = new BaseDAL();
        $base->insert($data, "sign");
        return $base->last_insert_id();
    }


    /** 更新用户信息 */
    public static function update($id, $data)
    {
        $base = new BaseDAL();
        return $base->update($id, $data, "sign");
    }

}
