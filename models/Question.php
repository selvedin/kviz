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
        return $this->hasMany(Options::class, ['question_id' => 'id']);
    }

    public function getPairs()
    {
        return $this->hasMany(Pairs::class, ['question_id' => 'id']);
    }

    public function getFields()
    {
        return [
            // ['label' => __('Content'), 'value' => $this->content],
            ['label' => __('Question Type'), 'value' => $this->questionType],
            ['label' => __('Content Type'), 'value' => $this->contentType],
            ['label' => __('Category'), 'value' => $this->category],
            ['label' => __('Status'), 'value' => $this->statusLabel],
            ['label' => __('Grade'), 'value' => $this->gradeLabel],
            ['label' => __('Level'), 'value' => $this->levelLabel],
        ];
    }


    public static function QuestionTypes()
    {
        return [1 => __('True/False'), __('Single choice'), __('Multiple choice'), __('Join pairs')];
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

    public static function Categories()
    {
        return [1 => __('Math'), __('Science'), __('Art'), __('Biology'), __('Chemistry')];
    }

    public function getCategory()
    {
        return $this->category_id ? self::Categories()[$this->category_id] : null;
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
        return ['1-3', '4-5', '6-7', '8-9', 'other'];
    }

    public function getGradeLabel()
    {
        return $this->grade > -1 ? self::Grades()[$this->grade] : null;
    }

    public static function Levels()
    {
        return [1 => __('Basic'), __('Regular'), __('Advances')];
    }

    public function getLevelLabel()
    {
        return $this->level ? self::Levels()[$this->level] : null;
    }

    public function beforeDelete()
    {
        foreach ($this->options as $o) $o->delete();
        return parent::beforeDelete();
    }
}
