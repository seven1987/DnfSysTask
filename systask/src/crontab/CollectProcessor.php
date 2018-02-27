<?php
/**
 * Created by PhpStorm.
 * User: xiaoda
 * Date: 2017/4/13
 * Time: 11:06
 */

namespace sysserver\crontab;

use application\services\SafeCenterService;
use common\models\Agent;
use common\models\Answer;
use common\models\Level;
use common\models\Profile;
use common\models\Test;
use common\services\UserLogsService;
use Yii;
use base\BaseHandler;

include 'simple_html_dom.php';

class CollectProcessor
{

    public function __construct()
    {
        BaseHandler::log("CollectProcessor Started");
    }

    public function run()
    {
        //@file_put_contents("collect.php","time:".date('Y-m-d H:i:s',time())."\n",FILE_APPEND);

        $url = 'http://www.weather.com.cn/';
        $html = file_get_html($url);

        if($html){
            $weather = $html->find('.myWeather');

            $model = new Test();
            $model->info = $weather;
            $model->id   = intval(date('YmdH',time()));

            if(Test::findOne($model->id)){
                Test::updateAll(['info'=>$html],['id'=>$model->id]);
            }else{
                $model->save();
            }
            echo $html;
        } else {
            echo 'Curl error: '  ;
        }
    }

}