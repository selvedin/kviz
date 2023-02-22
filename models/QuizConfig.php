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
class QuizConfig extends BaseModel
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
            [['quiz_id', 'num_of_questions', 'category_id'], 'required'],
            [[
                'quiz_id', 'num_of_questions', 'grade', 'level', 'category_id',
                'created_at', 'created_by', 'updated_at', 'updated_by'
            ], 'integer'],
        ];
    }

    public function getCategory()
    {
        return $this->hasOne(Categories::class, ['id' => 'category_id']);
    }

    public function getGradeLabel()
    {
        return $this->hasOne(Grade::class, ['id' => 'grade']);
    }

    public function getLevelLabel()
    {
        return $this->level ? Question::Levels()[$this->level] : null;
    }
}
