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
        'db_dm_game' => [
            'class' => 'yii\mongodb\Connection',
            'dsn' => 'mongodb://mongouser:ESGo4mUntLeCjdd9lp0beZJjcoqLlYop@192.168.50.29:27017/dm_game',
        ],
        'db_dm_member' => [
            'class' => 'yii\mongodb\Connection',
            'dsn' => 'mongodb://mongouser:ESGo4mUntLeCjdd9lp0beZJjcoqLlYop@192.168.50.29:27017/dm_member',
        ],
        'db_dm_data' => [
            'class' => 'yii\mongodb\Connection',
            'dsn' => 'mongodb://mongouser:ESGo4mUntLeCjdd9lp0beZJjcoqLlYop@192.168.50.29:27017/dm_data',
        ],
        'db_dm_admin' => [
            'class' => 'yii\mongodb\Connection',
            'dsn' => 'mongodb://mongouser:ESGo4mUntLeCjdd9lp0beZJjcoqLlYop@192.168.50.29:27017/dm_admin',
        ],
        'db_dm_his' => [
            'class' => 'yii\mongodb\Connection',
            'dsn' => 'mongodb://mongouser:ESGo4mUntLeCjdd9lp0beZJjcoqLlYop@192.168.50.29:27017/dm_his',
        ],
        'db_dm_log' => [
            'class' => 'yii\mongodb\Connection',
            'dsn' => 'mongodb://mongouser:ESGo4mUntLeCjdd9lp0beZJjcoqLlYop@192.168.50.29:27017/dm_log',
        ],
		//电竞资讯平台库
		'db_dm_info' => [
			'class' => 'yii\mongodb\Connection',
			'dsn' => 'mongodb://mongouser:ESGo4mUntLeCjdd9lp0beZJjcoqLlYop@192.168.50.29:27017/dm_info',
		],
//         'cache' => [
//             'class' => 'yii\caching\MemCache',
//             'servers' => [
//                 [
//                     'host' => '127.0.0.1',
//                     'port' => 11211,
//                     'weight' => 60,
//                 ],
//             ],
//         ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=192.168.50.25;dbname=core',
            'username' => 'root',
            'password' => 'mysql',
            'charset' => 'utf8',
            'emulatePrepare' => false,
            'attributes' => [
                PDO::ATTR_PERSISTENT => true
            ],
        ],
        'main' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=192.168.50.25;dbname=main',
            'username' => 'root',
            'password' => 'mysql',
            'charset' => 'utf8',
            'emulatePrepare' => false,
            'attributes' => [
                PDO::ATTR_PERSISTENT => true
            ],
        ],
        'common' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=192.168.50.25;dbname=common',
            'username' => 'root',
            'password' => 'mysql',
            'charset' => 'utf8',
            'emulatePrepare' => false,
            'attributes' => [
                PDO::ATTR_PERSISTENT => true
            ],
        ],
        'master' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=192.168.50.25;dbname=master',
            'username' => 'root',
            'password' => 'mysql',
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