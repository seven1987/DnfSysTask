<?php
/**
 * Created by PhpStorm.
 * User: xiaoda
 * Date: 2017/4/13
 * Time: 11:06
 */

namespace sysserver\crontab;

use application\services\SafeCenterService;
use common\models\Agent;
use common\models\Answer;
use common\models\Level;
use common\models\Profile;
use common\services\UserLogsService;
use common\utils\RedisKeys;
use common\utils\SysCode;
use Yii;
use base\BaseHandler;


class CollectProcessor
{

    public function __construct()
    {
        BaseHandler::log("CollectProcessor Started");
    }

    public function run()
    {
        @file_put_contents("collect.php","time:".date('Y-m-d H:i:s',time())."\n",FILE_APPEND);
    }

}