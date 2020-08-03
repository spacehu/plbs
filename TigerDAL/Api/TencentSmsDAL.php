<?php

namespace TigerDAL\Api;

use Exception;
use mod\init;
use stdClass;
use TigerDAL\BaseDAL;

require_once dirname(__DIR__) . '/../lib/qcloudsms_php/src/index.php';

use Qcloud\Sms\SmsSingleSender;
use Qcloud\Sms\SmsMultiSender;
use Qcloud\Sms\SmsVoiceVerifyCodeSender;
use Qcloud\Sms\SmsVoicePromptSender;
use Qcloud\Sms\SmsStatusPuller;
use Qcloud\Sms\SmsMobileStatusPuller;
use Qcloud\Sms\VoiceFileUploader;
use Qcloud\Sms\FileVoiceSender;
use Qcloud\Sms\TtsVoiceSender;

/**
 * Class SmsDemo
 *
 * Created on 17/10/17.
 * 短信服务API产品的DEMO程序,工程中包含了一个SmsDemo类，直接通过
 * 执行此文件即可体验语音服务产品API功能(只需要将AK替换成开通了云通信-短信服务产品功能的AK即可)
 * 备注:Demo工程编码采用UTF-8
 */
class TencentSmsDAL
{

    // 短信应用SDK AppID
    private static $appid; // 1400开头
    // 短信应用SDK AppKey
    private static $appkey;
    // 需要发送短信的手机号码
    //private $phoneNumbers = ["21212313123", "12345678902", "12345678903"];
    // 短信模板ID，需要在短信应用中申请
    private static $templateId;  // NOTE: 这里的模板ID`7839`只是一个示例，真实的模板ID需要在短信控制台中申请
    // 签名
    private static $smsSign; // NOTE: 这里的签名只是示例，请使用真实的已申请的签名，签名参数使用的是`签名内容`，而不是`签名ID`

    /**
     * 发送短信
     * @return stdClass
     */

    public static function sendSms($phone, $code, $orderid)
    {
        self::$appid = init::$config['env']['lib']['tencent']['sms']['appid'];
        self::$appkey = init::$config['env']['lib']['tencent']['sms']['appkey'];
        self::$templateId = init::$config['env']['lib']['tencent']['sms']['templateId'];
        self::$smsSign = init::$config['env']['lib']['tencent']['sms']['smsSign'];

        // 指定模板ID单发短信
        try {
            $ssender = new SmsSingleSender(self::$appid, self::$appkey);
            $params = [$code, '15'];
            return $ssender->sendWithParam("86", $phone, self::$templateId, $params, self::$smsSign, "", $orderid);  // 签名参数未提供或者为空时，会使用默认签名发送短信
        } catch (Exception $e) {
            echo var_dump($e);
        }
    }

    /** 确认发送频率 */
    public static function checkInsert($phone, $ip)
    {
        $base = new BaseDAL();
        $_sql = "select count(1) as num from `" . $base->table_name("sms") . "` where `phone`='" . $phone . "' and `ip`='" . $ip . "' and `date`='" . date("Ymd") . "';";
        $query = $base->getFetchRow($_sql);
        //return $query;
        if ($query['num'] >= 10) {
            return false;
        }
        return true;
    }

    /** 插入发送记录 */
    public static function insert($data)
    {
        $base = new BaseDAL();
        $base->insert($data,"sms");
        return $base->last_insert_id();
    }

    /** 更新用户信息 */
    public static function update($id, $data)
    {
        $base = new BaseDAL();
        return $base->update($id, $data, "sms");
    }

}
