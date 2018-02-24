<?php
return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,
    'appVersion' => '0.8.0',
    'appName' => 'DM',
    'homePage' => 'http://www.dmgame111.com',

    'uploadService' => 'https://upload.hces888.com/',

    'frontend_url' => ['pc_url' => 'https://www.hces888.com/', 'h5_url' => 'https://m.hces888.com/'],

    'tokenSignerKey' => 'BpFsnGKtY7Gl5Pa3kSm4HSezpLuRQC2h',            // token 签名使用的 key
    'tokenExpirationTime' => 60 * 60 * 24 * 30,    // token 过期时间，单位秒，30 天
    'platformApi' => '103.196.125.42:9991',         // 平台Api
    'platformAgentID' => 1,                        // 平台Agent
    'platformTID' => 80,                           //平台对接电竞api的厅主id
    'platformAPIName' => 'bi',                     //平台对接电竞api的接口名称

    'rabbitmq' => [
        'host' => '10.200.45.51',
        'port' => '5675',
        'login' => 'dmuser',
        'password' => 'BQLOJHH9lMzzKYKM6',
        'vhost'=>'/'
    ],
    'backendSwoole' => "wss://bmsg.hces888.com",
    'frontendSwoole' => "wss://fmsg.hces888.com",
    'sysserver'=>[
        "host"    =>  "10.200.45.52",
        "port"  =>  8004
    ],
    'backendmsg'=>[
        'host' => '10.200.45.51',
        'port' => '9508',
    ],
    'frontendmsg'=>[
        'host' => '10.200.45.51',
        'port' => '9507',
    ],
    //swoole 日志服务
    'logserver'=>[
        'host' => '10.200.45.52',
        'port' => '7004',
    ],
    'reckonserver'=>[
        "host"    =>  "10.200.45.52",
        "port"  =>  9004
    ],

	//系统支持的多语言
	'allow_lang' => [
		'zh-CN' => '简体',
		'zh-TW' => '繁体',
		'en-US' => 'En(US)',
        'ms-MY' => 'Melayu',
	],
];
