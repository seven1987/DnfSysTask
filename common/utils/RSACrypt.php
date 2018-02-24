<?php
/**
 * Created by PhpStorm.
 * User: zhangw
 * Date: 2018/1/15
 * Time: 18:03
 */
namespace common\utils;
use Yii;

class RSACrypt{
    /**
     * openssl 双向加密
     *
     * @return Encrypt|null
     */
    public static function  xmcrypt()
    {
        static $mt = null;
        if (is_null($mt)) {
            $appConfig = Yii::$app->params['backend_input']['app'];
            //        $key = api()->config()->read('app')['app_key'];
            $key = $appConfig['app_key'];
            $mt  = new Encrypt($key);
        }

        return $mt;
    }

    /**
     * @return array
     */
    public static function needFilterKeywords()
    {
        return ['email', 'mobile', 'qq', 'weixin', 'skype', 'idcard', 'telephone', 'card', 'account'];
    }

    /**
     * 加密
     *
     * @param string $data
     * @return string
     */
    public static function rsaEncrypt(string $data = null)
    {
        if (!strlen($data)) {
            return $data;
        }
        $mt = RSACrypt::xmcrypt()->encrypt($data);
        return  $mt?$mt:$data;
    }

    /**
     * 解密
     *
     * @param string $data
     * @return null|string
     */
    public static function rsaDecrypt(string $data = null)
    {
        if (!strlen($data)) {
            return $data;
        }

        $mt = RSACrypt::xmcrypt()->decrypt($data);
        // todo: 如果无法还原，返回空还是原加密字串？
        return  $mt?$mt:$data;
    }

    /**
     * 个人信息加、解密补丁
     *
     * @param array $data 多维数组
     * @param int   $handler Enc 加密 Dec 解密
     * @return array
     */
    public static function rsaPatch(array &$data = null, int $handler = Encrypt::DECRYPT)
    {
        if (!$data) {
            return $data;
        }
        foreach ($data as $key => &$datum) {
            if (is_array($datum)) {
                $datum = RSACrypt::rsaPatch($datum, $handler);
            } else {
                if (in_array($key, RSACrypt::needFilterKeywords(), true)) {
                    $datum = $handler == Encrypt::DECRYPT ? RSACrypt::rsaDecrypt($datum) : RSACrypt::rsaEncrypt($datum);
                }
            }
        }

        return $data;
    }

}