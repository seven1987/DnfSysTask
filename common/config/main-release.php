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
            'hostname' => '10.200.45.64',
            'port' => 6382,
            'password' => 'ESLe4gmraQxYJ3dFN2mTF0TpZZ0M6Oqf',
        ],
        // 原生 redis 类，效率较高，而且可以添加自定义方法
        'redisService' => [
            'class' => 'common\services\RedisService',
            'config' => [
                'hostname' => '10.200.45.64',
                'port' => 6382,
                'password' => 'ESLe4gmraQxYJ3dFN2mTF0TpZZ0M6Oqf',
            ],
        ],
        'session' => [
            'class' => 'yii\redis\Session',
            'timeout' => 3600*24,
            'name' => 'DMBACKID',
            'redis' => [
                'hostname' => '10.200.45.64',
                'port' => 6382,
                'password' => 'ESLe4gmraQxYJ3dFN2mTF0TpZZ0M6Oqf',
            ],
            'keyPrefix' => 'dm:session:',
        ],
        'security' => [
            'passwordHashCost' => 4,
        ],

        'mongodb' => [
            'class' => 'yii\mongodb\Connection',
            'dsn' => 'mongodb://mongouser:ESGo4mUntLeCjdd9lp0beZJjcoqLlYop@10.200.45.62:27017,10.200.45.63:27017,10.200.45.64:27017/djgame?replicaSet=esReplset&readPreference=primaryPreferred',
        ],
        'db_dm_game' => [
            'class' => 'yii\mongodb\Connection',
            'dsn' => 'mongodb://mongouser:ESGo4mUntLeCjdd9lp0beZJjcoqLlYop@10.200.45.62:27017,10.200.45.63:27017,10.200.45.64:27017/dm_game?replicaSet=esReplset&readPreference=primaryPreferred',
        ],
        'db_dm_member' => [
            'class' => 'yii\mongodb\Connection',
            'dsn' => 'mongodb://mongouser:ESGo4mUntLeCjdd9lp0beZJjcoqLlYop@10.200.45.62:27017,10.200.45.63:27017,10.200.45.64:27017/dm_member?replicaSet=esReplset&readPreference=primaryPreferred',
        ],
        'db_dm_data' => [
            'class' => 'yii\mongodb\Connection',
            'dsn' => 'mongodb://mongouser:ESGo4mUntLeCjdd9lp0beZJjcoqLlYop@10.200.45.62:27017,10.200.45.63:27017,10.200.45.64:27017/dm_data?replicaSet=esReplset&readPreference=primaryPreferred',
        ],
        'db_dm_admin' => [
            'class' => 'yii\mongodb\Connection',
            'dsn' => 'mongodb://mongouser:ESGo4mUntLeCjdd9lp0beZJjcoqLlYop@10.200.45.62:27017,10.200.45.63:27017,10.200.45.64:27017/dm_admin?replicaSet=esReplset&readPreference=primaryPreferred',
        ],
        'db_dm_his' => [
            'class' => 'yii\mongodb\Connection',
            'dsn' => 'mongodb://mongouser:ESGo4mUntLeCjdd9lp0beZJjcoqLlYop@10.200.45.62:27017,10.200.45.63:27017,10.200.45.64:27017/dm_his?replicaSet=esReplset&readPreference=primaryPreferred',
        ],
        'db_dm_log' => [
            'class' => 'yii\mongodb\Connection',
            'dsn' => 'mongodb://mongouser:ESGo4mUntLeCjdd9lp0beZJjcoqLlYop@10.200.45.62:27017,10.200.45.63:27017,10.200.45.64:27017/dm_log?replicaSet=esReplset&readPreference=primaryPreferred',
        ],
		'db_dm_info' => [
            'class' => 'yii\mongodb\Connection',
            'dsn' => 'mongodb://mongouser:ESGo4mUntLeCjdd9lp0beZJjcoqLlYop@10.200.45.62:27017,10.200.45.63:27017,10.200.45.64:27017/dm_info?replicaSet=esReplset&readPreference=primaryPreferred',
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
            'dsn' => 'mysql:host=10.200.45.61;port=3306;dbname=dm_game',
            'username' => 'es_admin',
            'password' => 'J5BPT2Q4bkUd6hrbxiS2',
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