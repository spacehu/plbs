<?php

namespace TigerDAL\Api;

use TigerDAL\BaseDAL;

class ExamDAL
{
    /** 根据试卷id获取答题结果 */
    public static function getByExaminationId($id)
    {
        $base = new BaseDAL();
        $sql = "select * from " . $base->table_name("exam") . " where `delete`=0 and examination_id=" . $id . " order by point desc  limit 1 ;";
        return $base->getFetchRow($sql);
    }

    /** 获取用户信息 */
    public static function getOne($id)
    {
        $base = new BaseDAL();
        $sql = "select * from " . $base->table_name("exam") . " where `delete`=0 and id=" . $id . "  limit 1 ;";
        return $base->getFetchRow($sql);
    }

    /** 插入 */
    public static function insertExam($data)
    {
        $base = new BaseDAL();
        $base->insert($data, "exam");
        return $base->last_insert_id();
    }


    /** 更新用户信息 */
    public static function update($id, $data)
    {
        $base = new BaseDAL();
        return $base->update($id, $data, "exam");
    }

    /** 删除用户信息 */
    public static function delete($id)
    {
        $base = new BaseDAL();
        $data = [
            'delete' => 1,
        ];
        return $base->update($id, $data, "exam");
    }

}
