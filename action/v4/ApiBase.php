<?php

namespace action\v4;

use mod\common as Common;
use TigerDAL\Api\ImageDAL;
use TigerDAL\Cms\CategoryDAL;
use TigerDAL\Cms\SystemDAL;
use TigerDAL\Api\ArticleDAL;
use config\code;

class ApiBase extends \action\RestfulApi {

    public $user_id;
    public $server_id;

    /**
     * 主方法引入父类的基类
     * 责任是分担路由的工作
     */
    function __construct() {
        $path = parent::__construct();
        $this->post = Common::exchangePost();
        $this->get = Common::exchangeGet();
        $this->header = Common::exchangeHeader();
        if (!empty($path)) {
            $_path = explode("-", $path);
            $actEval = "\$res = \$this ->" . $_path['2'] . "();";
            eval($actEval);
            exit(json_encode($res));
        }
    }

    /** 企业||用户 信息 */
    function categorys() {
        try {
            //轮播列表

            $res = array_values(CategoryDAL::getCategorys(1, 96, '', 1));
            if (!empty($res)) {
                foreach ($res as $k => $v) {
                    if (!empty($v['media_id'])) {
                        $_obj[] = $v['media_id'];
                    }
                }
                $_media_ids = implode(",", $_obj);
                $_medias = ImageDAL::getImages($_media_ids);
                if (is_string($_medias)) {
                    self::$data['success'] = false;
                    self::$data['data']['error_msg'] = $_medias;
                    self::$data['msg'] = code::$code[$_medias];
                    return self::$data;
                }
                foreach ($res as $k => $v) {
                    $_res[$k] = $v;
                    if (!empty($v['media_id'])) {
                        $_res[$k]['src'] = $_medias[$v['media_id']]['original_src'];
                    } else {
                        $_res[$k]['src'] = '';
                    }
                }
            }

            self::$data['data']['list'] = $_res;
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::HOME_INDEX], code::HOME_INDEX, json_encode($ex));
        }
        return self::$data;
    }


    /** 机遇 城市 信息 */
    function citys() {
        try {
            //轮播列表
            $res = ArticleDAL::getCitys();
            self::$data['data']['list'] = $res;
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::HOME_INDEX], code::HOME_INDEX, json_encode($ex));
        }
        return self::$data;
    }
    /** 机遇 类型 信息 */
    function types() {
        try {
            //轮播列表
            $res = ArticleDAL::getTypes();
            self::$data['data']['list'] = $res;
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::HOME_INDEX], code::HOME_INDEX, json_encode($ex));
        }
        return self::$data;
    }

    /** 机遇 检查是否开启 */
    function checkSystem(){
        try {
            //轮播列表
            $obj="0";
            $res = SystemDAL::getConfig('changes');
            if(!empty($res)){
                $obj=$res['value'];
            }
            self::$data['data']['info'] = $obj;
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::HOME_INDEX], code::HOME_INDEX, json_encode($ex));
        }
        return self::$data;
    }

    /** 获取系统配置的400电话 */
    function getPhone(){
        try {
            //轮播列表
            $obj="0";
            $res = SystemDAL::getConfig('company_phone');
            if(!empty($res)){
                $obj=$res['value'];
            }
            self::$data['data']['info'] = $obj;
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::HOME_INDEX], code::HOME_INDEX, json_encode($ex));
        }
        return self::$data;
    }
}
