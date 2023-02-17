<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "quiz_questions".
 *
 * @property int $id
 * @property int $quiz_id
 * @property int $question_id
 * @property float|null $duration
 * @property int|null $created_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 */
class QuizQuestions extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'quiz_questions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['quiz_id', 'question_id'], 'required'],
            [['quiz_id', 'question_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['duration'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'quiz_id' => 'Quiz ID',
            'question_id' => 'Question ID',
            'duration' => 'Duration',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }
}
