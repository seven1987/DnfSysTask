<?php
return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,
    'appVersion' => '0.8.0',
    'appName' => 'DM',
    'homePage' => 'http://www.dmgame111.com',

    'uploadService' => 'http://192.168.50.24:8071/',

    'frontend_url' => ['pc_url' => 'http://192.168.50.24:9787/', 'h5_url' => 'http://192.168.50.24:9786/'],

    'tokenSignerKey' => 'testing',            // token 签名使用的 key
    'tokenExpirationTime' => 60 * 60 * 24 * 30,    // token 过期时间，单位秒，30 天
    'platformAgentID' => 1,                        // 平台Agent
    'platformTID' => 80,                           //平台对接电竞api的厅主id
    'platformAPIName' => 'bi',                     //平台对接电竞api的接口名称

    'rabbitmq' => [
        'host' => '192.168.50.29',
        'port' => '5672',
        'login' => 'dmuser',
        'password' => 'BQLOJHH9lMzzKYKM6',
        'vhost'=>'/'
    ],
    'backendSwoole' => "ws://192.168.50.24:9502",
    'frontendSwoole' => "ws://192.168.50.24:9501",
    'sysserver'=>[
        "host"    =>  "192.168.50.24",
        "port"  =>  8001
    ],
    'backendmsg'=>[
        'host' => '192.168.50.24',
        'port' => '9502',
    ],
    'frontendmsg'=>[
        'host' => '192.168.50.24',
        'port' => '9501',
    ],
    //swoole 日志服务
    'logserver'=>[
        'host' => '192.168.50.24',
        'port' => '7001',
    ],
    'reckonserver'=>[
        "host"    =>  "192.168.50.24",
        "port"  => '9001'
    ],
	//系统支持的多语言
	'allow_lang' => [
		'zh-CN' => '简体',
		'zh-TW' => '繁体',
		'en-US' => 'En(US)',
        'ms-MY' => 'Melayu',
	],
];
