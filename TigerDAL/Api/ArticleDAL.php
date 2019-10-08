<?php

namespace TigerDAL\Api;

use TigerDAL\BaseDAL;

class ArticleDAL {

    /** 获取用户信息列表 */
    public static function getAll($currentPage, $pagesize, $keywords, $cat_id = '', $enterprise_id = '', $type = '') {
        $base = new BaseDAL();
        $limit_start = ($currentPage - 1) * $pagesize;
        $limit_end = $pagesize;
        $where = "";
        if (!empty($keywords)) {
            $where .= " and c.name like '%" . $keywords . "%' ";
        }
        if ($cat_id !== '') {
            $where .= " and c.cat_id = '" . $cat_id . "' ";
        }
        if ($enterprise_id !== '') {
            $where .= " and (c.enterprise_id = '" . $enterprise_id . "' or c.enterprise_id=0 or c.enterprise_id='' or c.enterprise_id is null) ";
        }
        if ($type !== '') {
            $where .= " and type = '" . $type . "' ";
        }
        $sql = "select c.*,i.original_src from " . $base->table_name("article") . " as c "
                . "left join " . $base->table_name("image") . " as i on i.id=c.media_id "
                . "where c.`delete`=0 " . $where . " "
                . "order by c.edit_time desc "
                . "limit " . $limit_start . "," . $limit_end . " ;";
        return $base->getFetchAll($sql);
    }

    /** 获取数量 */
    public static function getTotal($keywords, $cat_id = '', $enterprise_id = '', $type = '') {
        $base = new BaseDAL();
        $where = "";
        if (!empty($keywords)) {
            $where .= " and name like '%" . $keywords . "%' ";
        }
        if ($cat_id !== '') {
            $where .= " and cat_id = '" . $cat_id . "' ";
        }
        if ($enterprise_id !== '') {
            $where .= " and (enterprise_id = '" . $enterprise_id . "' or enterprise_id=0 or enterprise_id='' or enterprise_id is null) ";
        }
        if ($type !== '') {
            $where .= " and type = '" . $type . "' ";
        }
        $sql = "select count(1) as total from " . $base->table_name("article") . " where `delete`=0 " . $where . " limit 1 ;";
        return $base->getFetchRow($sql)['total'];
    }

    /** 获取用户信息 */
    public static function getOne($id) {
        $base = new BaseDAL();
        $sql = "select c.*,i.original_src from " . $base->table_name("article") . " as c "
                . "left join " . $base->table_name("image") . " as i on i.id=c.media_id "
                . "where c.`delete`=0 and c.id=" . $id . "  limit 1 ;";
        return $base->getFetchRow($sql);
    }

    /** 获取用户信息 */
    public static function getByName($name, $type) {
        $base = new BaseDAL();
        if (!empty($type)) {
            $where .= " and type = '" . $type . "' ";
        }
        $sql = "select * from " . $base->table_name("article") . " where `delete`=0 and name='" . $name . "' " . $where . "  limit 1 ;";
        return $base->getFetchRow($sql);
    }

    /** 新增用户信息 */
    public static function insert($data) {
        try {
            $base = new BaseDAL();
            if (is_array($data)) {
                foreach ($data as $v) {
                    if (is_numeric($v)) {
                        $_data[] = " " . $v . " ";
                    } else {
                        $_data[] = " '" . $v . "' ";
                    }
                }
                $set = implode(',', $_data);
                $sql = "insert into " . $base->table_name('article') . " values (null," . $set . ");";
                //echo $sql;die;
                $res = $base->query($sql);
                $id = $base->last_insert_id();
                if ($res) {
                    return $id;
                }
            } else {
                return true;
            }
        } catch (Exception $ex) {
            \TigerDAL\CatchDAL::markError(\config\code::$code[\config\code::WORKS_UPDATE], \config\code::WORKS_UPDATE, json_encode($ex));
        }
    }

    /** 更新用户信息 */
    public static function update($id, $data) {
        $base = new BaseDAL();
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                if (is_numeric($v)) {
                    $_data[] = " `" . $k . "`=" . $v . " ";
                } else {
                    $_data[] = " `" . $k . "`='" . $v . "' ";
                }
            }
            $set = implode(',', $_data);
            $sql = "update " . $base->table_name('article') . " set " . $set . "  where id=" . $id . " ;";
            //echo $sql;die;
            return $base->query($sql);
        } else {
            return true;
        }
    }

    /** 删除用户信息 */
    public static function delete($id) {
        $base = new BaseDAL();
        $sql = "update " . $base->table_name('article') . " set `delete`=1  where id=" . $id . " ;";
        return $base->query($sql);
    }

    public static function getCitys(){
        $base = new BaseDAL();
        $sql = "select city from " . $base->table_name("article") . " "
                . "where `delete`=0 and city is not null and city <> '' "
                . "group by city ;";
        return $base->getFetchAll($sql);
    }
    
    public static function getTypes(){
        $base = new BaseDAL();
        $sql = "select type from " . $base->table_name("article") . " "
                . "where `delete`=0 and type is not null and type <> '' "
                . "group by type ;";
        return $base->getFetchAll($sql);
    }
}
