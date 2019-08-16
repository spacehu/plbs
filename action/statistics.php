<?php

namespace action;

use TigerDAL;
use TigerDAL\Cms\EnterpriseDAL;
use TigerDAL\Cms\StatisticsDAL;
use config\code;
use mod\common as Common;

class statistics {

    private $class;
    public static $data;
    private $enterprise_id;

    function __construct() {
        $this->class = str_replace('action\\', '', __CLASS__);
        try {
            $_enterprise = EnterpriseDAL::getByUserId(Common::getSession("id"));
            if (!empty($_enterprise)) {
                $this->enterprise_id = $_enterprise['id'];
            } else {
                $this->enterprise_id = '';
            }
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::CATEGORY_INDEX], code::CATEGORY_INDEX, json_encode($ex));
        }
    }

    function staticPage() {
        Common::isset_cookie();

        \mod\init::getTemplate('admin', $this->class . '_' . __FUNCTION__);
    }

    /**
     * index function
     * get type
     * get startTime
     * get endTime
     * get action
     * get page
     * 
     * return 
     * $data['data']['pv']
     * $data['data']['uv']
     */
    function index() {
        Common::isset_cookie();
        try {
            $type = $_GET['type'];
            $_startTime = isset($_GET['startTime']) ? $_GET['startTime'] : date("Y-m-d", time());
            self::$data['startTime'] = $_startTime;
            $_endTime = isset($_GET['endTime']) ? $_GET['endTime'] : date("Y-m-d", strtotime("+1 day"));
            self::$data['endTime'] = $_endTime;
            if ($type == 'visit') {
                self::$data['data']['pv'] = StatisticsDAL::getPageView($_startTime, $_endTime);
                self::$data['data']['iv'] = StatisticsDAL::getIPView($_startTime, $_endTime);
                self::$data['data']['uv'] = StatisticsDAL::getUserView($_startTime, $_endTime);
            } else if ($type == 'action') {
                $_action = isset($_GET['action']) ? $_GET['action'] : 'index';
                self::$data['action'] = $_action;
                self::$data['actionList'] = \mod\init::$config['actionList'];
                self::$data['data']['pv'] = StatisticsDAL::getPageView($_startTime, $_endTime, $_action);
                self::$data['data']['iv'] = StatisticsDAL::getIPView($_startTime, $_endTime, $_action);
                self::$data['data']['uv'] = StatisticsDAL::getUserView($_startTime, $_endTime, $_action);
            } else if ($type == 'page') {
                $_url = isset($_GET['page']) ? $_GET['page'] : 'https://www.plbs.com';
                self::$data['page'] = $_url;
                self::$data['data']['pv'] = StatisticsDAL::getPageView($_startTime, $_endTime, '', $_url);
                self::$data['data']['iv'] = StatisticsDAL::getIPView($_startTime, $_endTime, '', $_url);
                self::$data['data']['uv'] = StatisticsDAL::getUserView($_startTime, $_endTime, '', $_url);
            }
            //Common::pr(self::$data);
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::STATISTICS_INDEX], code::STATISTICS_INDEX, json_encode($ex));
        }
        \mod\init::getTemplate('admin', $this->class . '_' . __FUNCTION__ . '_' . $type);
    }

    /**
     * getBonus function
     * get startTime
     * get endTime
     * 
     * return
     * $data['data']
     */
    function getBonus() {
        Common::isset_cookie();
        try {
            $_source = isset($_GET['source']) ? $_GET['source'] : "thanksgiving";
            self::$data['source'] = $_source;
            $_startTime = isset($_GET['startTime']) ? $_GET['startTime'] : date("Y-m-d", time());
            //$_startTime = isset($_GET['startTime']) ? $_GET['startTime'] : "2017-01-01";
            self::$data['startTime'] = $_startTime;
            $_endTime = isset($_GET['endTime']) ? $_GET['endTime'] : date("Y-m-d", strtotime("+1 day"));
            self::$data['endTime'] = $_endTime;

            $currentPage = isset($_GET['currentPage']) ? $_GET['currentPage'] : 1;
            $pagesize = isset($_GET['pagesize']) ? $_GET['pagesize'] : \mod\init::$config['page_width'];
            self::$data['currentPage'] = $currentPage;
            self::$data['pagesize'] = $pagesize;

            self::$data['data'] = StatisticsDAL::getBonus($currentPage, $pagesize, $_startTime, $_endTime, $_source);
            self::$data['total'] = StatisticsDAL::getBonusTotal($_startTime, $_endTime, $_source);
            self::$data['sources'] = StatisticsDAL::getSource();

//            Common::pr(self::$data);
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::STATISTICS_INDEX], code::STATISTICS_INDEX, json_encode($ex));
        }
        \mod\init::getTemplate('admin', $this->class . '_' . __FUNCTION__);
    }

    /**
     * getUser function 
     * 
     * 
     * 
     */
    function getStatisticsUser() {
        Common::isset_cookie();
        try {
            self::$data['data']['sex'] = StatisticsDAL::getSex();
            self::$data['data']['age'] = StatisticsDAL::getAge();
            self::$data['data']['region'] = StatisticsDAL::getRegion();

//            Common::pr(self::$data);
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::STATISTICS_INDEX], code::STATISTICS_INDEX, json_encode($ex));
        }
        \mod\init::getTemplate('admin', $this->class . '_' . __FUNCTION__);
    }

    /** 成员在线学习 */
    function customerList() {
        Common::isset_cookie();
        try {
            if ($this->enterprise_id == '') {
                Common::js_alert_redir("您不是企业管理员无法查看企业统计数据", ERROR_405);
                exit;
            }
            $currentPage = isset($_GET['currentPage']) ? $_GET['currentPage'] : 1;
            $pagesize = isset($_GET['pagesize']) ? $_GET['pagesize'] : \mod\init::$config['page_width'];
            $keywords = isset($_GET['keywords']) ? $_GET['keywords'] : "";
            $_startTime = isset($_GET['startTime']) ? $_GET['startTime'] : date("Y-m-d", time());
            $_endTime = isset($_GET['endTime']) ? $_GET['endTime'] : date("Y-m-d", strtotime("+1 day"));

            $data = StatisticsDAL::getCustomerList($currentPage, $pagesize, $keywords, $this->enterprise_id, $_startTime, $_endTime);
            self::$data['data'] = $data['data'];
            self::$data['total'] = $data['total'];

            self::$data['currentPage'] = $currentPage;
            self::$data['pagesize'] = $pagesize;
            self::$data['keywords'] = $keywords;
            self::$data['endTime'] = $_endTime;
            self::$data['startTime'] = $_startTime;
            self::$data['class'] = $this->class;


            //Common::pr(self::$data);
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::STATISTICS_INDEX], code::STATISTICS_INDEX, json_encode($ex));
        }
        \mod\init::getTemplate('admin', $this->class . '_' . __FUNCTION__);
    }

    /** 员工信息维护 */
    function userList() {
        Common::isset_cookie();
        try {
            if ($this->enterprise_id == '') {
                Common::js_alert_redir("您不是企业管理员无法查看企业统计数据", ERROR_405);
                exit;
            }
            $currentPage = isset($_GET['currentPage']) ? $_GET['currentPage'] : 1;
            $pagesize = isset($_GET['pagesize']) ? $_GET['pagesize'] : \mod\init::$config['page_width'];
            $keywords = isset($_GET['keywords']) ? $_GET['keywords'] : "";

            $data = StatisticsDAL::getUserList($currentPage, $pagesize, $keywords, $this->enterprise_id);
            self::$data['data'] = $data['data'];
            self::$data['total'] = $data['total'];

            self::$data['currentPage'] = $currentPage;
            self::$data['pagesize'] = $pagesize;
            self::$data['keywords'] = $keywords;
            self::$data['class'] = $this->class;
            //Common::pr(self::$data);
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::STATISTICS_INDEX], code::STATISTICS_INDEX, json_encode($ex));
        }
        \mod\init::getTemplate('admin', $this->class . '_' . __FUNCTION__);
    }

    /** 在线课程学习 */
    function courseList() {
        Common::isset_cookie();
        try {
            if ($this->enterprise_id == '') {
                Common::js_alert_redir("您不是企业管理员无法查看企业统计数据", ERROR_405);
                exit;
            }
            $currentPage = isset($_GET['currentPage']) ? $_GET['currentPage'] : 1;
            $pagesize = isset($_GET['pagesize']) ? $_GET['pagesize'] : \mod\init::$config['page_width'];
            $keywords = isset($_GET['keywords']) ? $_GET['keywords'] : "";
            $_startTime = isset($_GET['startTime']) ? $_GET['startTime'] : date("Y-m-d", time());
            $_endTime = isset($_GET['endTime']) ? $_GET['endTime'] : date("Y-m-d", strtotime("+1 day"));


            $data = StatisticsDAL::getCourseList($currentPage, $pagesize, $keywords, $this->enterprise_id, $_startTime, $_endTime);
            self::$data['data'] = $data['data'];
            self::$data['total'] = $data['total'];

            self::$data['currentPage'] = $currentPage;
            self::$data['pagesize'] = $pagesize;
            self::$data['keywords'] = $keywords;
            self::$data['endTime'] = $_endTime;
            self::$data['startTime'] = $_startTime;
            self::$data['class'] = $this->class;
            //Common::pr(self::$data);
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::STATISTICS_INDEX], code::STATISTICS_INDEX, json_encode($ex));
        }
        \mod\init::getTemplate('admin', $this->class . '_' . __FUNCTION__);
    }

}
