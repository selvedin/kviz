<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pairs".
 *
 * @property int $id
 * @property int $question_id
 * @property string $one
 * @property string $two
 * @property int|null $created_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 */
class Pairs extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pairs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['question_id', 'one', 'two'], 'required'],
            [['question_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['one', 'two'], 'string', 'max' => 1024],
        ];
    }
}
