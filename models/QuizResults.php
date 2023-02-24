<?php

namespace app\models;

use Yii;
use yii\web\HttpException;

/**
 * This is the model class for table "quiz_results".
 *
 * @property int $id
 * @property int $quiz_id
 * @property int $temp_id
 * @property int $competitor_id
 * @property string $results
 * @property string $summary
 * @property string $totals
 * @property int|null $created_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 */
class QuizResults extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'quiz_results';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['quiz_id', 'temp_id', 'competitor_id', 'results'], 'required'],
            [['quiz_id', 'temp_id', 'competitor_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['results', 'summary', 'totals'], 'string'],
        ];
    }

    public static function add($quiz, $summary, $totals)
    {
        if ($quiz->results) {
            $model = QuizResults::find()->where(['quiz_id' => $quiz->quiz_id, 'temp_id' => $quiz->id, 'competitor_id' => Yii::$app->user->id])->one();
            if (!$model)
                $model = new QuizResults([
                    'quiz_id' => $quiz->quiz_id,
                    'temp_id' => $quiz->id,
                    'competitor_id' => Yii::$app->user->id,
                ]);
            $model->results = $quiz->results;
            $model->summary = serialize($summary);
            $model->totals = serialize(['totalCorrect' => $totals]);
            if (!$model->save()) throw new HttpException(500, json_encode($model->errors));
        }
    }

    public static function getSummary($quiz, $temp, $competitor)
    {
        $model = self::find()->where(['quiz_id' => $quiz, 'temp_id' => $temp, 'competitor_id' => $competitor])->select(['results'])->one();
        if (!$model) return null;
    }
}
