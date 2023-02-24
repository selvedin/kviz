<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "quiz_competitors".
 *
 * @property int $id
 * @property int $quiz_id
 * @property int $temp_id
 * @property int $competitor_id
 * @property int $team_id
 * @property int|null $created_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 */
class QuizCompetitors extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'quiz_competitors';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['quiz_id', 'competitor_id'], 'required'],
            [[
                'quiz_id', 'temp_id', 'competitor_id', 'team_id',
                'created_at', 'created_by', 'updated_at', 'updated_by'
            ], 'integer'],
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'competitor_id']);
    }
}
