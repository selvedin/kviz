<?php

namespace app\models;

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
}
