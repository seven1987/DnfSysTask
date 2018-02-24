<?php

namespace common\utils\translate;

/**
*翻译类
**/
class TranslateTools
{
	private static $translatorList = [
		'baidu' => 'common\utils\translate\TranslateBaidu',
		'google' => 'common\utils\translate\TranslateGoogle',
	];

	public static function getInstance($type='baidu')
	{
		static $_translator = null;
		if(is_null($_translator))
		{
			$_translator = new static::$translatorList[$type]();
		}
		return $_translator;
	}

	public static function t($query, $to, $from='zh', $type='baidu')
	{
		$translator = static::getInstance($type);
		$ret = $translator->t($query, $from, $to);
		return isset($ret['trans_result']) ? $ret['trans_result'][0]['dst'] : '';
	}
}

?>