<?php

namespace common\utils\translate;

//翻译通用接口定义
class TranslateInterface
{
	 //翻译入口
    public function t($query, $from, $to)
    {
    }

    //获取语言对应的翻译接口语言代码
    public function getLangCode($lang)
    {
    }

}