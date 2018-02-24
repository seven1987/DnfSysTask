<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 2017/1/7
 * Time: 11:23
 */

namespace common\models;

use common\services\IDService;
use common\services\RedisService;
use common\services\TranslateService;
use common\utils\CommonFun;
use common\utils\LangDictConfig;
use common\utils\translate\Translate;
use common\utils\translate\TranslateTools;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use yii\db\Expression;
use yii\db\ActiveQueryInterface;
use yii\base\InvalidConfigException;


abstract class Base extends \yii\mongodb\ActiveRecord
{
    const TYPE_PK = 'pk';
    const TYPE_UPK = 'upk';
    const TYPE_BIGPK = 'bigpk';
    const TYPE_UBIGPK = 'ubigpk';
    const TYPE_CHAR = 'char';
    const TYPE_STRING = 'string';
    const TYPE_TEXT = 'text';
    const TYPE_SMALLINT = 'smallint';
    const TYPE_INTEGER = 'integer';
    const TYPE_BIGINT = 'bigint';
    const TYPE_FLOAT = 'float';
    const TYPE_DOUBLE = 'double';
    const TYPE_DECIMAL = 'decimal';
    const TYPE_DATETIME = 'datetime';
    const TYPE_TIMESTAMP = 'timestamp';
    const TYPE_TIME = 'time';
    const TYPE_DATE = 'date';
    const TYPE_BINARY = 'binary';
    const TYPE_BOOLEAN = 'boolean';
    const TYPE_MONEY = 'money';

    public static function tableName()
    {
        return '{{%' . Inflector::camel2id(StringHelper::basename(get_called_class()), '_') . '}}';
    }

    public static function getColumnType()
    {
        return '{{%' . Inflector::camel2id(StringHelper::basename(get_called_class()), '_') . '}}';
    }

    /**
     * Finds ActiveRecord instance(s) by the given condition.
     * This method is internally called by [[findOne()]] and [[findAll()]].
     * @param mixed $condition please refer to [[findOne()]] for the explanation of this parameter
     * @return ActiveQueryInterface the newly created [[ActiveQueryInterface|ActiveQuery]] instance.
     * @throws InvalidConfigException if there is no primary key defined
     * @internal
     */
    protected static function findByCondition($condition)
    {
        $query = static::find();

        //primary key:
        if (!ArrayHelper::isAssociative($condition)) {
            // query by primary key
            $primaryKey = static::primaryKey();
            if (isset($primaryKey[0])) {
                $value = static::typecast($primaryKey[0], $condition);
                $condition = [$primaryKey[0] => $value];
            } else {
                throw new InvalidConfigException('"' . get_called_class() . '" must have a primary key.');
            }
        }

        return $query->andWhere($condition);
    }

    /**
     * Sets the attribute values in a massive way.
     * @param array $values attribute values (name => value) to be assigned to the model.
     * @param boolean $safeOnly whether the assignments should only be done to the safe attributes.
     * A safe attribute is one that is associated with a validation rule in the current [[scenario]].
     * @see safeAttributes()
     * @see attributes()
     */
    public function setAttributes($values, $safeOnly = true)
    {
        if (is_array($values)) {
            $attributes = array_flip($safeOnly ? $this->safeAttributes() : $this->attributes());
            foreach ($values as $name => $value) {
                $value = static::typecast($name, $value);
                if (isset($attributes[$name])) {
                    $this->$name = $value;
                } elseif ($safeOnly) {
                    $this->onUnsafeAttribute($name, $value);
                }
            }
        };
    }

    /**
     * Updates all documents in the collection using the provided attribute values and conditions.
     * For example, to change the status to be 1 for all customers whose status is 2:
     *
     * ```php
     * Customer::updateAll(['status' => 1], ['status' => 2]);
     * ```
     *
     * @param array $attributes attribute values (name-value pairs) to be saved into the collection
     * @param array $condition description of the objects to update.
     * Please refer to [[Query::where()]] on how to specify this parameter.
     * @param array $options list of options in format: optionName => optionValue.
     * @return int the number of documents updated.
     */
    public static function updateAll($attributes, $condition = [], $options = [])
    {
        foreach ($attributes as $name => $value) {
            $value = static::typecast($name, $value);
            $attributes[$name] = $value;
        }
        return static::getCollection()->update($condition, $attributes, $options);
    }

    /**
     * 更新操作增加表字段中文检测
     * @param bool $runValidation
     * @param null $attributeNames
     * @return false|int
     */
    public function update($runValidation = true, $attributeNames = null){
        $names = $this->attributes();
        foreach ($names as $name) {
            if ($name == "_id") continue;
            //多语言处理
            static::handleLang($name, $this->getAttribute($name), $this->tableName());
        }

        return parent::update($runValidation,$attributeNames);
    }

    public function insert($runValidation = true, $attributes = null)
    {
        if ($this->tableName() == "idgenerator") {
            return;
        }

        //increase key id:
        if (count($this->primaryKey()) == 1 && strstr($this->primaryKey()[0], "id") != false) {
            $name = $this->primaryKey()[0];
            if (!$this->getAttribute($name)) {
                $nextId = IDService::getNextID(static::tableName());
                $this->setAttribute($name, $nextId);
            }
        }

        //type convert:
        $names = $this->attributes();
        foreach ($names as $name) {
            if ($name == "_id") continue;
            //多语言处理
            static::handleLang($name,$this->getAttribute($name),$this->tableName());
            $value = static::typecast($name, $this->getAttribute($name));
            $this->setAttribute($name, $value);
        }

        if ($runValidation && !$this->validate($attributes)) {
            return false;
        }
        $result = $this->insertInternal($attributes);

        return $result;
    }

    public static function typecast($name, $value)
    {

        $type = static::getColumnType()[$name];

        if ($value === '' && $type !== Base::TYPE_TEXT && $type !== Base::TYPE_STRING
            && $type !== Base::TYPE_BINARY && $type !== Base::TYPE_CHAR
        ) {
            return null;
        }
        if ($value === null || gettype($value) === $type || $value instanceof Expression) {
            return $value;
        }
        switch ($type) {
            case 'resource':
            case Base::TYPE_STRING:
            case Base::TYPE_CHAR:
                if (is_resource($value)) {
                    return $value;
                }
                if (is_float($value)) {
                    // ensure type cast always has . as decimal separator in all locales
                    return str_replace(',', '.', (string)$value);
                }
                return (string)$value;
            case Base::TYPE_BIGINT:
                return (string)$value;
            case Base::TYPE_INTEGER:
            case Base::TYPE_SMALLINT:
                return (int)$value;
            case Base::TYPE_BOOLEAN:
                // treating a 0 bit value as false too
                // https://github.com/yiisoft/yii2/issues/9006
                return (bool)$value && $value !== "\0";
            case Base::TYPE_DOUBLE:
                return (double)$value;
        }

        return $value;
    }

    /**
     * mongo产生的中文信息，追加到多语言配置文件中
     * @param $col
     * @param string $value
     * @param null $tableName
     */
    protected static function handleLang($col,$value='',$tableName=null)
    {
        //只对中文处理
        $value = trim($value);
        if(empty($value) || !preg_match('/[\x{4e00}-\x{9fa5}]/u ', $value))
        {
            return;
        }

        if($tableName==null){
            //根据调用者的类名获得对应的表名
            $tableName = Inflector::camel2id(StringHelper::basename(get_called_class()), '_');
        }

        //根据表名过滤
        $info = LangDictConfig::MESSAGES_TO_BE_TRANSLATED[$tableName];
        if(isset($info)){
            if(empty($info['cols']))return;

            //根据字段过滤
            if(in_array($col,$info['cols'])){
                if (LangDict::findOne(['msg' => $value]) == null) {
                    $model = new LangDict();
                    $model->category = 'backend_input';
                    $model->msg = $value;
                    $model->zh_cn = $value;
                    $model->zh_tw = '';
                    $model->en_us = '';
                    $model->updatetime = date('Y-m-d H:i:s', time());
                    $model->deleted = 0;
                    $model->save();
                }
            }
        }

        /*
		//后台输入翻译文件内容
		$zhCN = 'zh-CN';
		$backendInput = isset(Yii::$app->params['backend_input']) ? Yii::$app->params['backend_input'] : 'backend_input';
		$backendInputZhCNFile = COMMON_PATH . 'languages/' . $zhCN . '/' . $backendInput . '.php';
		//写入多语言配置
		CommonFun::writeConfigFile($backendInputZhCNFile, [$value => $value]);*/
    }

}

