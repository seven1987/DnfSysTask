<?php

namespace common\utils;

use Yii;

class SMSSender
{
    const TYPE_SIGNUP = 1;          // 验证码类型：注册
    const TYPE_RESETPWD = 2;        // 验证码类型：找回密码

    const APIKEY = '03d0b1460108b690b1f86294282f838b';

    //多语言支持， 在common/languages/{LANG}/frontend.php定义
    const SMS_CODE_TEMPLATE = '【赏金猎人】尊敬的用户，你的验证码是：%d，请在10分钟内输入。请勿告诉其他人。';
    const SMS_MSG_TEMPLATE = '【赏金猎人】您已成功注册爱乐网，请联系支持人员安排对接测试。';

    // 取得用户信息，叮咚云上面的管理账户信息，包含短信条数额度
    public static function getUser()
    {
        $ch = self::initCurl();
        curl_setopt($ch, CURLOPT_URL, 'https://api.dingdongcloud.com/v1/sms/userinfo');
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('apikey' => self::APIKEY)));
        $result = curl_exec($ch);
        self::closeCurl($ch);
        return $result;
    }

    // 发送验证码短信（国内）
    public static function sendYZM($type, $mobile)
    {
        $ch = self::initCurl();
        $code = self::initCode($type, $mobile);
        $content = sprintf(Yii::t('frontend',self::SMS_CODE_TEMPLATE), $code);
        $data = array('content' => urlencode($content), 'apikey' => self::APIKEY, 'mobile' => $mobile);
        curl_setopt($ch, CURLOPT_URL, 'https://api.dingdongcloud.com/v1/sms/sendyzm');
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        $result = curl_exec($ch);
        self::closeCurl($ch);
        return json_decode($result, true);
    }

    // 发送语音验证码
    public static function sendYYYZM($mobile)
    {
        $ch = self::initCurl();
        $code = self::initCode(self::TYPE_SIGNUP, $mobile);
        $data = array('content' => urlencode($code), 'apikey' => self::APIKEY, 'mobile' => $mobile);
        curl_setopt($ch, CURLOPT_URL, 'https://api.dingdongcloud.com/v1/sms/sendyyyzm');
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        $result = curl_exec($ch);
        self::closeCurl($ch);
        return $result;
    }

    // 发送通知信息
    public static function sendTZ($mobile)
    {
        $ch = self::initCurl();
        $data = array('content' => urlencode(Yii::t('frontend',self::SMS_MSG_TEMPLATE)), 'apikey' => self::APIKEY, 'mobile' => $mobile);
        curl_setopt($ch, CURLOPT_URL, 'https://api.dingdongcloud.com/v1/sms/sendtz');
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        $result = curl_exec($ch);
        self::closeCurl($ch);
        return $result;
    }

    // 发送营销信息
    public static function sendYX($mobile)
    {
        $ch = self::initCurl();
        $content = Yii::t('frontend', '【叮咚云】您已成功注册叮咚云，请联系支持人员安排对接测试。退订回t');
        $data = array('content' => urlencode($content), 'apikey' => self::APIKEY, 'mobile' => $mobile);
        curl_setopt($ch, CURLOPT_URL, 'https://api.dingdongcloud.com/v1/sms/sendyx');
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        $result = curl_exec($ch);
        self::closeCurl($ch);
        return $result;
    }

    private static function initCurl()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept:text/plain;charset=utf-8', 'Content-Type:application/x-www-form-urlencoded', 'charset=utf-8')); // 设置验证方式
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // 设置返回结果为流
        curl_setopt($ch, CURLOPT_TIMEOUT, 10); // 设置超时时间
        curl_setopt($ch, CURLOPT_POST, 1); // 设置通信方式
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        return $ch;
    }

    private static function closeCurl(&$ch)
    {
        curl_close($ch);
        unset($ch);
    }

    private static function initCode($type, $mobile)
    {
        $code = rand(1000, 9999);
        Yii::$app->redisService->getRedis()->set(self::getRedisKey($type) . $mobile, $code, 60 * 10);
        return $code;
    }

    public static function getCode($type, $mobile)
    {
        $code = Yii::$app->redisService->getRedis()->get(self::getRedisKey($type) . $mobile);
        return $code;
    }

    private static function getRedisKey($type)
    {
        switch ($type) {
            case self::TYPE_SIGNUP:
                return RedisKeys::KEY_SMS_SIGNUP;
            case self::TYPE_RESETPWD:
                return RedisKeys::KEY_SMS_RESETPWD;
        }
    }
}