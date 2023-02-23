<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "quiz_temp".
 *
 * @property int $id
 * @property int $quiz_id
 * @property string|null $quiz
 * @property string|null $results
 * @property int|null $created_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 */
class QuizTemp extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'quiz_temp';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['quiz_id'], 'required'],
            [['quiz_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['quiz', 'results'], 'string'],
        ];
    }

    public static function addQuiz($id, $quiz)
    {
        $model = self::find()->where("quiz_id=$id AND created_by=" . Yii::$app->user->id . " AND results IS NULL")->one();
        if ($model) $model->quiz = $quiz;
        else $model = new QuizTemp(['quiz_id' => $id, 'quiz' => $quiz]);
        $model->save();
    }

    public static function getById($id)
    {
        return QuizTemp::find()->where(['quiz_id' => $id, 'created_by' => Yii::$app->user->id])->one();
    }

    public static function getEmptyById($id)
    {
        return QuizTemp::find()->where("quiz_id=$id AND created_by=" . Yii::$app->user->id . " AND results IS NULL")->one();
    }
}
