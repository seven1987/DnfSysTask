<?php
return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,
    'appVersion' => '0.8.0',
    'appName' => 'DM',
    'homePage' => 'http://www.dmgame111.com',

    'uploadService' => 'http://192.168.50.21:8072/',

    'tokenSignerKey' => 'testing',            // token 签名使用的 key
    'tokenExpirationTime' => 60 * 60 * 24 * 30,    // token 过期时间，单位秒，30 天
    'platformApi' => '192.168.50.22:9791',         // 平台Api
    'platformAgentID' => 1,                        // 平台Agent
    'platformTID' => 80,                           //平台对接电竞api的厅主id
    'platformAPIName' => 'bi',                     //平台对接电竞api的接口名称

    'rabbitmq' => [
        'host' => '192.168.10.200',
        'port' => '5672',
        'login' => 'guest',
        'password' => 'admin',
        'vhost'=>'debug'
    ],
    'backendSwoole' => "ws://192.168.10.200:9506",
    'frontendSwoole' => "ws://192.168.10.200:9505",
    'sysserver'=>[
        "host"    =>  "192.168.10.200",
        "port"  =>  8003
    ],
    'backendmsg'=>[
        'host' => '0.0.0.0',
        'port' => '9506',
    ],
    'frontendmsg'=>[
        'host' => '0.0.0.0',
        'port' => '9505',
    ],
    //swoole 日志服务
    'logserver'=>[
        'host' => '192.168.10.200',
        'port' => '7003',
    ],
    'reckonserver'=>[
        "host"    =>  "192.168.10.200",
        "port"  =>  9003
    ],

	//系统支持的多语言
	'allow_lang' => [
		'zh-CN' => '简体',
		'zh-TW' => '繁体',
		'en-US' => 'En(US)',
        'ms-MY' => 'Melayu',
	],
];
