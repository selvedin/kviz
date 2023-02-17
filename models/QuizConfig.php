<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "quiz_config".
 *
 * @property int $id
 * @property int $quiz_id
 * @property int $num_of_questions
 * @property int $grade
 * @property int $level
 * @property int $category_id
 * @property int|null $created_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 */
class QuizConfig extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'quiz_config';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['quiz_id', 'num_of_questions', 'grade', 'level', 'category_id'], 'required'],
            [['quiz_id', 'num_of_questions', 'grade', 'level', 'category_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
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
            'num_of_questions' => 'Num Of Questions',
            'grade' => 'Grade',
            'level' => 'Level',
            'category_id' => 'Category ID',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }
}
