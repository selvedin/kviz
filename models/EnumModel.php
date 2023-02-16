<?php

namespace app\models;

use yii\helpers\ArrayHelper;

class EnumModel extends \yii\db\ActiveRecord
{

  /**
   * {@inheritdoc}
   */
  public function rules()
  {
    return [
      [['title'], 'required'],
      [['title'], 'unique'],
      [['title'], 'string', 'max' => 256],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function attributeLabels()
  {
    return [
      'id' => 'ID',
      'title' => __('Title'),
    ];
  }

  public static function isImported()
  {
    return self::find()->count();
  }

  public static function add($value)
  {
    $modelName = get_called_class();
    $model = self::find()->where(['title' => $value])->one();
    if ($model) return $model->id;
    $model = new $modelName(['title' => $value]);
    $model->save();
    return $model->id;
  }

  public static function getOrCreate($title)
  {
    $modelName = get_called_class();
    $model = self::find()->where(['title' => $title])->one();
    if ($model) return $model->id;
    $model = new $modelName(['title' => $title]);
    $model->save();
    return $model->id;
  }

  public static function list()
  {
    return ArrayHelper::map(self::find()->all(), 'id', 'title');
  }
}
