<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "quiz_results".
 *
 * @property int $id
 * @property int $quiz_id
 * @property int $question_id
 * @property int $competitor_id
 * @property int $answer_id
 * @property int|null $created_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 */
class QuizResults extends \yii\db\ActiveRecord
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
            [['quiz_id', 'question_id', 'competitor_id', 'answer_id'], 'required'],
            [['quiz_id', 'question_id', 'competitor_id', 'answer_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
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
            'competitor_id' => 'Competitor ID',
            'answer_id' => 'Answer ID',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }
}
