<?php
ini_set('default_socket_timeout', -1);

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

define('ROOT_PATH', dirname(__DIR__));

define('SYSSERVER_ROOT', ROOT_PATH.'/sysserver');
require ROOT_PATH . '/vendor/autoload.php';
require_once ROOT_PATH . '/vendor/yiisoft/yii2/Yii.php';
require_once ROOT_PATH . '/common/config/bootstrap.php';
require_once(SYSSERVER_ROOT . '/config/bootstrap.php');

$config = yii\helpers\ArrayHelper::merge(
    require ROOT_PATH . '/common/config/main-develop.php',
    require SYSSERVER_ROOT . '/config/main.php'
);

$yiiApp = new yii\console\Application($config);

require_once(SYSSERVER_ROOT.'/classes.php');

$betProcessor = new \sysserver\bet\BetProcessor($config['params'], $config['components']['redis']);
while(true){
	$betProcessor->run();
	usleep(1000*1000);
}

