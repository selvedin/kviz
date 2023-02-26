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
 * @property int|null $quiz_type
 * @property int|null $school_id
 * @property int|null $moderator_id
 * @property int|null $created_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 */
class Quiz extends BaseModel
{

    public $activeNum;

    const TYPE_SELF = 1;
    const TYPE_REMOTE = 2;

    const STATUS_PENDING = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_STARTED = 2;
    const STATUS_RUNNING = 3;
    const STATUS_FINISHED = 9;
    const STATUS_ARCHIVED = 10;
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
                'moderator_id', 'created_at', 'created_by', 'updated_at', 'updated_by',
                'quiz_type', 'activeNum'
            ], 'integer'],
            [['title'], 'string', 'max' => 1024],
        ];
    }

    public function getConfig()
    {
        return $this->hasMany(QuizConfig::class, ['quiz_id' => 'id']);
    }

    public function getHistory()
    {
        return $this->hasMany(QuizTemp::class, ['quiz_id' => 'id'])->andWhere("results IS NOT NULL");
    }

    public function getPending()
    {
        return $this->hasMany(QuizTemp::class, ['quiz_id' => 'id'])->andWhere("active=" . self::STATUS_PENDING);
    }

    public function getActive()
    {
        return $this->hasMany(QuizTemp::class, ['quiz_id' => 'id'])->andWhere("active=" . self::STATUS_ACTIVE);
    }

    public function getStarted()
    {
        return $this->hasMany(QuizTemp::class, ['quiz_id' => 'id'])->andWhere("active=" . self::STATUS_STARTED);
    }

    public function getRunning()
    {
        return $this->hasMany(QuizTemp::class, ['quiz_id' => 'id'])->andWhere("active=" . self::STATUS_RUNNING);
    }

    public function getArchived()
    {
        return $this->hasMany(QuizTemp::class, ['quiz_id' => 'id'])->andWhere("active=" . self::STATUS_ARCHIVED);
    }

    public function getGradeLabel()
    {
        return $this->hasOne(Grade::class, ['id' => 'grade']);
    }

    public function getFields()
    {
        return [
            // ['label' => __('Title'), 'value' => $this->title],
            ['label' => __('Moderator'), 'value' => $this->moderator?->name],
            ['label' => __('Num Of Questions'), 'value' => $this->num_of_questions],
            ['label' => __('Duration'), 'value' => $this->duration . ' ' . __('minutes')],
            ['label' => __('Time for answer'), 'value' => round($this->duration * 60 / $this->num_of_questions) . ' ' . __('seconds')],
            ['label' => __('Quiz type'), 'value' => $this->quizType],
            ['label' => __('Grade'), 'value' => $this->gradeLabel?->title],
            ['label' => __('Level'), 'value' => $this->levelLabel],
        ];
    }

    public function getModerator()
    {
        return $this->hasOne(User::class, ['id' => 'moderator_id']);
    }

    public function getStatusLabel()
    {
        return $this->status > -1 ? Question::Statuses()[$this->status] : null;
    }

    public function getLevelLabel()
    {
        return $this->level ? Question::Levels()[$this->level] : null;
    }

    public function generateQuestions($cache = true)
    {
        $questions = [];
        if ($cache) $existing = QuizTemp::getEmptyById($this->id);
        if (isset($existing)) return ['id' => $existing->id, 'questions' => unserialize($existing->quiz)];
        if (!count($this->config))
            $questions = Question::generateGuestions("status = 1", $this->num_of_questions);
        else
            foreach ($this->config as $conf) {
                $limit = $conf->num_of_questions;
                $questions = Question::generateGuestions($this->createWhere($conf), $limit);
            }
        shuffle($questions);
        $tempId = QuizTemp::addQuiz($this->id, serialize($questions), $cache);
        return ['id' => $tempId, 'questions' => $questions];
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

    public function getQuizType()
    {
        switch ($this->quiz_type) {
            case self::TYPE_SELF:
                return __('Self running');
            case self::TYPE_REMOTE:
                return __('Remote controled');
            default:
                return null;
        }
    }

    public static function getTypes()
    {
        return [self::TYPE_SELF => __("Self running"), self::TYPE_REMOTE => __("Remote controled")];
    }

    public function beforeSave($insert)
    {
        $this->num_of_questions = $this->configNumber ? $this->configNumber : $this->num_of_questions;

        return parent::beforeSave($insert);
    }
}
