<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "quiz".
 *
 * @property int $id
 * @property string $title
 * @property int|null $num_of_questions
 * @property int|null $duration
 * @property int|null $grade
 * @property int|null $level
 * @property int|null $school_id
 * @property int|null $moderator_id
 * @property int|null $created_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 */
class Quiz extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'quiz';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['num_of_questions', 'duration', 'grade', 'level', 'school_id', 'moderator_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['title'], 'string', 'max' => 1024],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'num_of_questions' => 'Num Of Questions',
            'duration' => 'Duration',
            'grade' => 'Grade',
            'level' => 'Level',
            'school_id' => 'School ID',
            'moderator_id' => 'Moderator ID',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }
}
