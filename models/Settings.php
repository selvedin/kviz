<?php

namespace app\models;

use app\helpers\CacheHelper;
use Yii;

/**
 * This is the model class for table "settings".
 *
 * @property int $id
 * @property string $name
 * @property string|null $title
 * @property string|null $type
 * @property string|null $parent
 * @property string|null $str_value
 * @property string|null $text_value
 * @property int|null $int_value
 * @property float|null $decimal_value
 * @property string|null $created_on
 * @property int|null $created_by
 * @property string|null $updated_on
 * @property int|null $updated_by
 */
class Settings extends BaseModel
{
    const COMPANY_SETTING_KEY = 'company_info';
    const GENERAL_SETTING_KEY = 'general_settings';
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'settings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['text_value'], 'string'],
            [['int_value', 'created_by', 'updated_by'], 'integer'],
            [['decimal_value'], 'number'],
            [['name', 'title', 'str_value', 'type', 'parent'], 'string', 'max' => 512],
            [['created_on', 'updated_on'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return $this->getLabels();
    }

    public function mapToJson()
    {
        return [
            'id' => $this->id, 'name' => $this->name,
            'title' => $this->title, 'type' => $this->type,
            'parent' => $this->parent,
            'text_value' => in_array($this->type, ['Objects', 'Enumeration']) ? json_decode($this->text_value) : $this->text_value
        ];
    }

    public static function getEnums($name)
    {
        $all = [];
        $enums = self::find()->where("type='Enumeration' and name='$name'")->select(["id", "text_value"])->all();
        foreach ($enums as $enum)  $all[$enum->id] = json_decode($enum->text_value, true)[0];
        return $all;
    }

    public static function getEnum($id)
    {
        $model = self::findOne($id);
        return isset($model) ? json_decode($model->text_value, true)[0] : null;
    }

    public static function getFullEnum($id)
    {
        $model = self::findOne($id);
        return isset($model) ? json_decode($model->text_value, true) : null;
    }

    public static function getByTypeAndName($type, $name)
    {
        $model = self::find()->where("name='$type' AND text_value='[\"$name\"]'")->select(['id', 'name', 'text_value'])->one();
        return isset($model) ? $model->id : null;
    }

    public static function getIdDelivery()
    {
        $model = self::find()->where("name='Contact Type' AND text_value='[\"Delivery\"]'")->select(['id', 'name', 'text_value'])->one();
        return isset($model) ? $model->id : null;
    }

    public static function getFields($title)
    {
        $model = self::find()->where("type='Objects' and name='$title'")->one();
        return isset($model) ? json_decode($model->text_value, true) : null;
    }

    public static function getGeneral($type)
    {
        $all = [
            self::COMPANY_SETTING_KEY => [
                'name' => __('Name'),
                'address' => __('Address'),
                'email' => __('Email'),
                'mobile' => __('Mobile'),
                'phone' => __('Phone')
            ],
            self::GENERAL_SETTING_KEY => [
                'check_email_notification' => __('Email notifications are turned on'),
                'number_message_duration' => __('Message duration [sec]'),
            ]
        ];
        return isset($type, $all[$type]) ? $all[$type] : null;
    }

    public static function getSetting($type)
    {
        $data = CacheHelper::get($type);
        if ($data == false) {
            $model = self::find()->where("type='$type'")->one();
            if (!isset($model)) {
                $name = explode("_", $type);
                $model = new Settings(['type' => $type, 'name' => ucwords(implode(' ', $name))]);
                $model->save();
            }
            $data = $model;
        }
        return $data;
    }

    public static function isEmailNotifications()
    {
        $general = self::getGeneralSettings();
        return isset($general['check_email_notification']);
    }

    public static function getMessageDuration()
    {
        $general = self::getGeneralSettings();
        return isset($general['number_message_duration']) ? (int)$general['number_message_duration'] : 5000;
    }

    public static function getCompanyInfo()
    {
        $model = self::getSetting(self::COMPANY_SETTING_KEY);
        return unserialize($model->text_value);
    }

    public static function getGeneralSettings()
    {
        $model = self::getSetting(self::GENERAL_SETTING_KEY);
        return unserialize($model->text_value);
    }

    public static function getLanguages()
    {
        return ['fr' => 'Français', 'en' => 'English'];
    }

    public static function getLang()
    {
        return isset($_COOKIE['lng']) ? $_COOKIE['lng'] : 'en';
    }

    public static function getAvailableLang()
    {
        return array_values(array_filter(array_keys(self::getLanguages()), function ($v) {
            return $v != self::getLang();
        }))[0];
    }

    /** After record is saved
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        CacheHelper::clearCache($this->type);
    }
}
