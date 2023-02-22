<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "question".
 *
 * @property int $id
 * @property string $content
 * @property int|null $question_type
 * @property int|null $content_type
 * @property int|null $category_id
 * @property int|null $status
 * @property int|null $grade
 * @property int|null $level
 * @property int|null $created_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 */
class Question extends BaseModel
{
    const TYPE_TRUE = 1;
    const TYPE_SINGLE = 2;
    const TYPE_MULTI = 3;
    const TYPE_JOIN = 4;
    const TYPE_INPUT = 5;
    const TYPE_ESSAI = 6;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'question';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['content'], 'required'],
            [['question_type', 'content_type', 'category_id', 'status', 'grade', 'level', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['content'], 'string', 'max' => 1024],
        ];
    }

    public function getOptions()
    {
        return $this->hasMany(Options::class, ['question_id' => 'id'])->orderBy('RAND()');
    }

    public function getPairs()
    {
        return $this->hasMany(Pairs::class, ['question_id' => 'id'])->orderBy('RAND()');
    }

    public function getCategory()
    {
        return $this->hasOne(Categories::class, ['id' => 'category_id']);
    }

    public function getGradeLabel()
    {
        return $this->hasOne(Grade::class, ['id' => 'grade']);
    }

    public function getFields()
    {
        return [
            // ['label' => __('Content'), 'value' => $this->content],
            ['label' => __('Question Type'), 'value' => $this->questionType],
            ['label' => __('Content Type'), 'value' => $this->contentType],
            ['label' => __('Category'), 'value' => $this->category?->name],
            ['label' => __('Status'), 'value' => $this->statusLabel],
            ['label' => __('Grade'), 'value' => $this->gradeLabel?->title],
            ['label' => __('Level'), 'value' => $this->levelLabel],
        ];
    }

    public static function QuestionTypes()
    {
        return [
            self::TYPE_TRUE => __('True/False'),
            self::TYPE_SINGLE => __('Single choice'),
            self::TYPE_MULTI => __('Multiple choice'),
            self::TYPE_JOIN => __('Join pairs'),
            self::TYPE_INPUT => __('Input result'),
            self::TYPE_ESSAI => __('Essai')
        ];
    }

    public function getQuestionType()
    {
        return $this->question_type ? self::QuestionTypes()[$this->question_type] : null;
    }

    public static function ContentTypes()
    {
        return [1 => __('Text'), __('Image'), __('Audio'), __('Video')];
    }

    public function getContentType()
    {
        return $this->content_type ? self::ContentTypes()[$this->content_type] : null;
    }

    public static function Statuses()
    {
        return [__('Pending'), __('Active'), __('Archived')];
    }

    public function getStatusLabel()
    {
        return $this->status > -1 ? self::Statuses()[$this->status] : null;
    }

    public static function Grades()
    {
        return Grade::list();
    }

    public static function Levels()
    {
        return [1 => __('Basic'), __('Regular'), __('Advances')];
    }

    public function getLevelLabel()
    {
        return $this->level ? self::Levels()[$this->level] : null;
    }

    public function getFirstOption()
    {
        return $this->options && isset($this->options[0]) ? $this->options[0]['content'] : null;
    }

    public function getIsTrue()
    {
        return $this->options && isset($this->options[0]) ? $this->options[0]['is_true'] : null;
    }

    public function OptionsAsArray()
    {
        $options = [];
        foreach ($this->options as $o) $options[] = ['id' => $o->id, 'content' => $o->content, 'is_true' => $o->is_true];
        return $options;
    }

    public function PairsAsArray()
    {
        $pairs = [];
        foreach ($this->pairs as $p) $pairs[] = ['id' => $p->id, 'one' => $p->one, 'two' => $p->two];
        $data['left'] = $pairs;
        shuffle($pairs);
        $data['right'] = $pairs;
        return $data;
    }

    public static function generateGuestions($where, $limit)
    {
        $questions = [];
        foreach (self::find()->where($where)
            ->select(['id', 'content', 'question_type'])
            ->orderBy('RAND()')
            ->limit($limit)->all() as $q) {
            $questions[] = [
                'id' => $q->id,
                'content' => $q->content,
                'question_type' => $q->question_type,
                'options' => $q->OptionsAsArray(),
                'pairs' => $q->PairsAsArray(),
            ];
        }
        return $questions;
    }

    public function beforeDelete()
    {
        foreach ($this->options as $o) $o->delete();
        foreach ($this->pairs as $p) $p->delete();
        return parent::beforeDelete();
    }
}
