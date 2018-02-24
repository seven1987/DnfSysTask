<?php
/**
 * Created by PhpStorm.
 * User: colen
 * Date: 2017/1/18
 * Time: 11:13
 */

namespace base;

use Yii;

abstract class BaseHandler
{

    public static function trace($msg, $category = 'systask')
    {
        Yii::trace($msg, $category);
        $time = date('Y-m-d H:i:s', time());
        echo $time . " " . $msg . "\n";
    }

    public static function log($msg, $category = 'systask')
    {
        Yii::info($msg, $category);
        $time = date('Y-m-d H:i:s', time());
        echo $time . " " . $msg . "\n";
    }

    public static function error($msg, $category = 'systask')
    {
        Yii::error($msg, $category);
        $time = date('Y-m-d H:i:s', time());
        echo $time . " " . $msg . "\n";
    }

    public static function warning($msg, $category = 'systask')
    {
        Yii::warning($msg, $category);
        $time = date('Y-m-d H:i:s', time());
        echo $time . " " . $msg . "\n";
    }
}