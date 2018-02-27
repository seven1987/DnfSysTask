<?php
namespace common\models;

use Yii;

trait TestTrait{

  /**
     * 返回数据库字段信息，用于mongodb与php数据类型转换
     */
    public static function getColumnType(){
        return array(
        'id' => 'integer',
		'info' => 'string',
		'created' => 'timestamp',
		'updated' => 'timestamp',
		     );
    }


    /**
    * 返回当前表所采用数据库
    */
    public static function getDb()
    {
        return \Yii::$app->get('db_dm_game');
    }

    /**
    * 返回当前表字段名,包括_id
    */
    public function attributes()
    {
        return ['_id','id','info','created','updated',];
    }

    /**
    * 返回表主键
    */
    public static function primaryKey()
    {
        return ['id',];
    }
}
