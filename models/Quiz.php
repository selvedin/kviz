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

    public function getConfig()
    {
        return $this->hasMany(QuizConfig::class, ['quiz_id' => 'id']);
    }

    public function getGradeLabel()
    {
        return $this->hasOne(Grade::class, ['id' => 'grade']);
    }

    public function getFields()
    {
        return [
            // ['label' => __('Title'), 'value' => $this->title],
            ['label' => __('Num Of Questions'), 'value' => $this->num_of_questions],
            ['label' => __('Duration'), 'value' => $this->duration],
            ['label' => __('Status'), 'value' => $this->statusLabel],
            ['label' => __('Grade'), 'value' => $this->gradeLabel?->title],
            ['label' => __('Level'), 'value' => $this->levelLabel],
            ['label' => __('Moderator'), 'value' => $this->moderator?->name],
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

    public function getLevelLabel()
    {
        return $this->level ? Question::Levels()[$this->level] : null;
    }

    public function generateQuestions()
    {
        $questions = [];
        $existing = QuizTemp::getEmptyById($this->id);
        if ($existing) return unserialize($existing->quiz);
        if (!count($this->config))
            $questions = Question::generateGuestions("status = 1", $this->num_of_questions);
        else
            foreach ($this->config as $conf) {
                $limit = $conf->num_of_questions;
                $questions = Question::generateGuestions($this->createWhere($conf), $limit);
            }
        shuffle($questions);
        QuizTemp::addQuiz($this->id, serialize($questions));
        return $questions;
    }

    private function createWhere($conf)
    {
        $where = "status = 1 AND category_id = $conf->category_id";
        if ($conf->question_type) $where .= " AND question_type=$conf->question_type";
        if ($conf->grade) $where .= " AND grade=$conf->grade";
        if ($conf->level) $where .= " AND level=$conf->level";
        return $where;
    }

    public function getConfigNumber()
    {
        $total = 0;
        foreach ($this->config as $conf) $total += $conf->num_of_questions;
        return $total;
    }

    public function beforeSave($insert)
    {
        $this->num_of_questions = $this->configNumber ? $this->configNumber : $this->num_of_questions;

        return parent::beforeSave($insert);
    }
}
