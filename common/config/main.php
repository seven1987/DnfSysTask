<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'timeZone' => 'Asia/Shanghai',
    'components' => [
        //多语言配置
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/languages',
                ],
            ],
        ],

        'cache' => [
           // 'class' => 'yii\caching\FileCache',
            'class' => 'yii\redis\Cache'
        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => '192.168.50.25',
            'port' => 6379,
            'database' => 2,
        ],
        // 原生 redis 类，效率较高，而且可以添加自定义方法
        'redisService' => [
            'class' => 'common\services\RedisService',
            'config' => [
                'hostname' => '192.168.50.25',
                'port' => 6379,
                'database' => 2,
            ],
        ],
        'redisMain' => [
            'class' => 'yii\redis\Connection',
            'hostname' => '192.168.50.25',
            'port' => 6379,
            'database' => 0,
        ],
        // 原生 redis 类，效率较高，而且可以添加自定义方法
        'redisMainService' => [
            'class' => 'common\services\RedisService',
            'config' => [
                'hostname' => '192.168.50.29',
                'port' => 6379,
                'database' => 0,
            ],
        ],
        'session' => [
            'class' => 'yii\redis\Session',
            'timeout' => 3600*24,
            'name' => 'DMBACKID',
            'redis' => [
                'hostname' => '192.168.50.29',
                'port' => 6379,
                'database' => 0,
            ],
            'keyPrefix' => 'dm:session:',
        ],
        'security' => [
            'passwordHashCost' => 4,
        ],

        'mongodb' => [
            'class' => 'yii\mongodb\Connection',
            'dsn' => 'mongodb://admin:!#$123456sensefun@192.168.50.29:27017/djgame',
        ],

        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=123.206.175.60;dbname=data',
            'username' => 'root',
            'password' => 'root',
            'charset' => 'utf8',
            'emulatePrepare' => false,
            'attributes' => [
                PDO::ATTR_PERSISTENT => true
            ],
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.163.com',
                'username' => 'yan12bo@163.com',
                'password' => 'yb18729036498',
                'port' => '25',
                'encryption' => 'tls',
            ],
            'messageConfig'=>[
                'charset'=>'UTF-8',
                'from'=>['yan12bo@163.com'=>'德玛西亚']
            ],
        ],
        //自定义系统日志
        'log' => [
            'flushInterval' => 1,
            'targets' => [
                //系统错误日志
                [
                    'class' => 'common\log\RedisTarget',
                    'redis' => 'redis',
                    'levels' => [ 'error', 'warning'],
                    'exportInterval' => 1,
//                    'categories' => '',
                ],
                //自定义分类日志
                [
                    'class' => 'common\log\RedisTarget',
                    'redis' => 'redis',
                    'levels' => ['info'],
                    'except' => ['application'],
                    'categories' => ['backend*','frontend*','sysserver*','msgserver*','logserver*','reckonserver*'],
                    'exportInterval' => 1,
                ],


            ],
        ],

    ],

];
?>