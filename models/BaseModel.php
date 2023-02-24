<?php

namespace app\models;

use Yii;
use yii\base\ModelEvent;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class BaseModel extends ActiveRecord
{

  public function behaviors()
  {
    return [
      [
        'class' => TimestampBehavior::class,
        'attributes' => [
          ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
          ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
        ],
        // if you're using datetime instead of UNIX timestamp:
        // 'value' => new Expression('NOW()'),
      ],
    ];
  }

  public function attributeLabels()
  {
    $all = [];
    foreach ($this->getTableSchema()->columnNames as $value)
      $all[$value] = __(ucwords(str_replace("_", " ", $value)));

    return $all;
  }

  public function beforeSave($insert)
  {
    $event = new ModelEvent();
    $this->trigger($insert ? self::EVENT_BEFORE_INSERT : self::EVENT_BEFORE_UPDATE, $event);
    if ($this->isNewRecord && $this->hasAttribute('created_by')) {
      $this->created_by = Yii::$app->user->id;
    } else if ($this->hasAttribute('updated_by')) {
      $this->updated_by = Yii::$app->user->id;
    }

    return $event->isValid;
  }

  public function isPrivate()
  {
    if (Yii::$app->user->isGuest) return true;
    if (Yii::$app->user->identity->role->private)
      if ($this->created_by != Yii::$app->user->id) return true;
    return false;
  }
}
