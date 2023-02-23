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

    public function getQuizObject()
    {
        return $this->hasOne(Quiz::class, ['id' => 'quiz_id']);
    }

    public function processResults()
    {
        if (!$this->results) return [];
        $data = [];
        $quiz = unserialize($this->quiz);
        $results = unserialize($this->results);
        $results = $results['results'];
        $totalCorrect = 0;
        foreach ($quiz as $q) {
            $result = getResult($q['id'], $results);
            $answer = isset($result['content']) ? $result['content'] : null;
            $question  = Question::findOne((int)$q['id']);
            $rightOptions = $question->CorrectOptionsAsString();

            $isCorrect = trim($answer) == trim($rightOptions);
            if ($isCorrect) $totalCorrect++;

            $data[] = [
                'id' => $q['id'],
                'title' => $q['content'],
                'options' => $question->OptionsAsString(),
                'correct' => $rightOptions,
                'answer' => $answer,
                'isCorrect' => $isCorrect
            ];
        }
        return ['items' => $data, 'totalCorrect' => $totalCorrect];
    }

    public static function addQuiz($id, $quiz)
    {
        $model = self::find()->where("quiz_id=$id AND active=1")->one();
        if ($model) $model->quiz = $quiz;
        else $model = new QuizTemp(['quiz_id' => $id, 'quiz' => $quiz, 'active' => 0]);
        $model->save();
        return $model->id;
    }

    public static function getById($id)
    {
        return QuizTemp::find()->where(['quiz_id' => $id, 'active' => 1])->one();
    }

    public static function getEmptyById($id)
    {
        return QuizTemp::find()->where("quiz_id=$id AND active=1")->one();
    }
}
