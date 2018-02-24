<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace common\log;

use common\utils\RedisKeys;
use Yii;
use yii\base\InvalidConfigException;
use yii\di\Instance;
use yii\helpers\VarDumper;
use yii\log\Logger;
use yii\redis\Connection;

/**
 * FileTarget records log messages in a file.
 *
 * The log file is specified via [[logFile]]. If the size of the log file exceeds
 * [[maxFileSize]] (in kilo-bytes), a rotation will be performed, which renames
 * the current log file by suffixing the file name with '.1'. All existing log
 * files are moved backwards by one place, i.e., '.2' to '.3', '.1' to '.2', and so on.
 * The property [[maxLogFiles]] specifies how many history files to keep.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class RedisTarget extends \yii\log\Target
{

    /**
     * @var Connection|array|string the Redis connection object or a configuration array for creating the object, or the application component ID of the Redis connection.
     */
    public $redis = 'redis';

    /**
     * @var string key of the Redis list to store log messages. Default to "log"
     */
    public $key = null;

    /**
     * @var 保留最新的日志数量， 其余的清除
     */
    public $limit_length = 1000000;

    /**
     * Initializes the route.
     * This method is invoked after the route is created by the route manager.
     */
    public function init()
    {
        parent::init();
        $this->redis = Instance::ensure($this->redis, Connection::className());
        $this->key = RedisKeys::KEY_SYSTEM_LOG;
    }

    /**
     * Writes log messages to a file.
     * @throws InvalidConfigException if unable to open the log file for writing
     */
    public function export()
    {
// 原始方法
//        $text = implode("\n", array_map([$this, 'formatMessage'], $this->messages)) . "\n";
//        Yii::$app->redisLog->lpush("log",$text);


// 自定义
        foreach ($this->messages as $message) {
            list($text, $level, $category, $timestamp) = $message;//分解日志数组
            $level = Logger::getLevelName($level);//日志等级名称
            //获得日志内容
            if (!is_string($text)) {
                // exceptions may not be serializable if in the call stack somewhere is a Closure
                if ($text instanceof \Throwable || $text instanceof \Exception) {
                    $text = (string) $text;
                } else {
                    $text = VarDumper::export($text);
                }
            }
            //获得当前操作用户id以及用户类型（后台管理用户或普通用户）
            $userId = 0;
            $userType = 0;
            if((php_sapi_name() != 'cli') && Yii::$app->user->getId())
            {
                $userId = Yii::$app->user->getId();
                if(isset(Yii::$app->user->identity->admintype))//管理员用户 user_type = 1
                {
                    $userType = 1;
                }
                else//普通用户 user_type = 2
                {
                    $userType = 2;
                }
            }
//			$text = $this->formatMessage($message);//日志内容格式化
            $data = [
                'logtime' => date('Y-m-d H:i:s', $timestamp),
                'user_id' => $userId,
                'user_type' => $userType,
                'category' => $category,
                'level' => $level,
                'message' => $text,
            ];
            $this->redis->executeCommand('RPUSH', [$this->key, serialize($data)]);//日志存入redis LIST

            //只保留最新的N条日志
            $nowLength = $this->redis->LLEN($this->key);
            if( $nowLength > $this->limit_length)
            {
                $trimPos = $nowLength - $this->limit_length;
                $this->redis->LTRIM($this->key, $trimPos, -1);
            }

        }
    }

}
