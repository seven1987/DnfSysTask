<?php

namespace common\utils;

class Timer
{
    static $beginTime = 0;
    static $endTime = 0;
    static $index = 0;
    static $logs = '';

    /**
     * 计时开始
     */
    public static function b()
    {
        static::$beginTime = microtime(true);
        static::$index++;
    }

    /**
     * 计时结束
     *
     * @param string $tag 标签
     * @return double 耗时
     */
    public static function e($tag = '')
    {
        static::$endTime = microtime(true);
        $time = bcsub(static::$endTime, static::$beginTime, 4);
        $log = '[' . ($tag == '' ? static::$index : $tag) . '] use time : ' . $time . '</br>';
        static::$logs .= $log;
        static::b();
        return $time;
    }

    public static function getLogs()
    {
        return static::$logs;
    }

}