<?php
/**
 * Created by PhpStorm.
 * User: colen
 * Date: 2017/1/13
 * Time: 11:21
 */

ini_set('default_socket_timeout', -1);

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

define('ROOT_PATH', dirname(__DIR__) . '/');
define('SYSTASK_ROOT', ROOT_PATH . "/systask/");
// define('RECKON_WORKER_NUMBER', 24);
define('YII_ENABLE_ERROR_HANDLER', false);

require_once(ROOT_PATH . '/vendor/yiisoft/yii2/Yii.php');
require_once(ROOT_PATH . '/common/config/bootstrap.php');
require_once(SYSTASK_ROOT . '/config/bootstrap.php');

$config = yii\helpers\ArrayHelper::merge(
    require(ROOT_PATH . '/common/config/main.php'),
    require(SYSTASK_ROOT . '/config/main.php')
);

$yiiapplication = new yii\web\Application($config);

require_once(SYSTASK_ROOT . '/classes.php');
use base\BaseHandler;

$ip = $config["params"]["systask"]["host"];
$port = $config["params"]["systask"]["port"];

$ip = '0.0.0.0';
$serv = new Swoole\Http\Server($ip, $port);

$serv->on('Request', function ($request, $response) {
    $response->end("connect success");
});

function runProcessor($server, $processor, $msg = "")
{
    try {
        $processor->run();
    } catch (\Exception $e) {
        echo $msg . " server die.\n" . $e->getTraceAsString() . "\n";
        echo "error msg:" . $e->getMessage() . "\n";
        echo "error code:" . $e->getCode() . "\n";
        BaseHandler::error($e->getTraceAsString());
        usleep(3 * 1000 * 1000);
        $server->shutdown();
    }
}

 //定时脚本子进程:
$userProcessor = new \swoole_process(function () use ($serv, $config) {
    new yii\web\Application($config);
    $processor = new \sysserver\crontab\CollectProcessor();
    while (true) {
        runProcessor($serv, $processor, "CollectProcessor");
        sleep(30);
        Yii::$app->db->close();
    }
});


/**
 * 添加进程
 */
$serv->addProcess($userProcessor);

//启动
$serv->start();

