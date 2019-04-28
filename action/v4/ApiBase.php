<?php

namespace action\v4;

use mod\common as Common;
use TigerDAL\Api\TokenDAL;
use TigerDAL\Api\ImageDAL;
use TigerDAL\Cms\CategoryDAL;
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
//        $TokenDAL = new TokenDAL();
//        $_token = $TokenDAL->checkToken();
//        //Common::pr($_token);die;
//        if ($_token['code'] != 90001) {
//            self::$data['success'] = false;
//            self::$data['data']['error_msg'] = 'tokenerror';
//            self::$data['data']['code'] = $_token['code'];
//            self::$data['msg'] = code::$code['tokenerror'];
//            exit(json_encode(self::$data));
//        }
//        $this->user_id = $_token['data']['user_id'];
//        $this->server_id = $_token['data']['server_id'];
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

            $res = array_values(CategoryDAL::getCategorys(1, 12, '', 1));
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

}
