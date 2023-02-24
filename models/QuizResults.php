<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "quiz_results".
 *
 * @property int $id
 * @property int $quiz_id
 * @property int $temp_id
 * @property int $competitor_id
 * @property string $results
 * @property int|null $created_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 */
class QuizResults extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'quiz_results';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['quiz_id', 'temp_id', 'competitor_id', 'results'], 'required'],
            [['quiz_id', 'temp_id', 'competitor_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['results'], 'string'],
        ];
    }
}
