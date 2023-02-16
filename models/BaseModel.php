<?php

namespace app\models;

use ReflectionClass;
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

  // public function visit()
  // {
  //   $reflect = new ReflectionClass($this);
  //   $model = Visits::find()->where(['object' => $reflect->getShortName(), 'id_object' => $this->id, 'year' => date('Y'), 'month' => date('m'), 'day' => date('d')])->one();
  //   if (isset($model)) {
  //     $model->updateCounters(['views' => 1]);
  //     return;
  //   }
  //   $model = new Visits(['object' => $reflect->getShortName(), 'id_object' => $this->id, 'year' => date('Y'), 'month' => date('m'), 'day' => date('d'), 'views' => 1]);
  //   $model->save();
  //   $this->updateCounters(['views' => 1]);
  // }
}
