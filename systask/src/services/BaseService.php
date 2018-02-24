<?php
/**
 * Created by PhpStorm.
 * User: SCF
 * Date: 2018/1/15
 * Time: 17:40
 */

namespace application\services;
use Yii;

class BaseService
{

    public function getMainDb(){
        $master = Yii::$app->get("master");
    }

}