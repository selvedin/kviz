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
class Question extends \yii\db\ActiveRecord
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

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'content' => 'Content',
            'question_type' => 'Question Type',
            'content_type' => 'Content Type',
            'category_id' => 'Category ID',
            'status' => 'Status',
            'grade' => 'Grade',
            'level' => 'Level',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }
}
