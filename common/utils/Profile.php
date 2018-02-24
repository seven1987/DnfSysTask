<?php
/**
 * User: jiayi
 * Date: 2017/3/10
 */

namespace common\utils;
use Yii;


class Profile
{
    static private $profileTokens = array();

    /**
     * 开始计时
     * @param $token
     * @return void
     */
    static public function begin($token)
    {
        Profile::$profileTokens[$token] = microtime(true);
        $time = date('Y-m-d H:i:s', time());
        $log = "profile start on: $time\n";
        Yii::info($log, $token);
        echo "[$token]$log";
    }

    /**
     * 结束计时
     * @param $token
     * @return void
     */
    static public function end($token)
    {
        if (!YII_DEBUG) {
            return;
        }

        if (isset(Profile::$profileTokens[$token])) {
            $time = microtime(true) - Profile::$profileTokens[$token];
            unset(Profile::$profileTokens[$token]);
        } else {
            $time = 0;
        }
        $log = "Cost time: " . sprintf('%.3fs', $time) . "\n";
        Yii::info($log, $token);
        echo "[$token]$log";
    }
}
