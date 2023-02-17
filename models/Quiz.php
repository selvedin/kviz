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
 * @property int|null $status
 * @property int|null $school_id
 * @property int|null $moderator_id
 * @property int|null $created_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 */
class Quiz extends BaseModel
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
            [[
                'num_of_questions', 'duration', 'grade', 'level', 'status', 'school_id',
                'moderator_id', 'created_at', 'created_by', 'updated_at', 'updated_by'
            ], 'integer'],
            [['title'], 'string', 'max' => 1024],
        ];
    }

    public function getModerator()
    {
        return $this->hasOne(User::class, ['id' => 'id']);
    }

    public function getStatusLabel()
    {
        return $this->status > -1 ? Question::Statuses()[$this->status] : null;
    }

    public function getGradeLabel()
    {
        return $this->grade > -1 ? Question::Grades()[$this->grade] : null;
    }

    public function getLevelLabel()
    {
        return $this->level ? Question::Levels()[$this->level] : null;
    }
}
