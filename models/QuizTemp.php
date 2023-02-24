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

    public function getUserResults()
    {
        return $this->hasMany(QuizResults::class, ['temp_id' => 'id']);
    }

    public function getCompetitors()
    {
        return $this->hasMany(QuizCompetitors::class, ['temp_id' => 'id']);
    }

    public function getCompetitorsJson()
    {
        $data = [];
        foreach ($this->competitors as $comp) $data[] = ['id' => $comp->id, 'competitor' => $comp->user->name];
        return $data;
    }

    public function processResults($id = 0)
    {
        $id = $id ? $id : Yii::$app->user->id;
        $final = [];
        $temp = unserialize($this->results);
        $results = unserialize($temp[$id]);
        $results = $results['results'];
        $processed = [];
        $num = 1;
        $totalCorrect = 0;
        foreach ($results as $result) {
            $question = Question::findOne($result['question']);
            if (in_array($question->id, $processed)) continue;
            if ($question->question_type == 3) {
                $processed[] = $question->id;
                $answer = $this->getOptions($question->id, $results);
            } else if ($question->question_type == 4) {
                $processed[] = $question->id;
                $answer = $this->getPairs($question->id, $results);
            } else
                $answer = $this->resolveAnswer($question, $result);
            $options = $this->resolveOptions($question);
            $correct = $this->resolveCorrect($question);
            $isCorrect = $answer == $correct;
            if ($isCorrect) $totalCorrect++;
            $final[] = [
                'id' => $question->id,
                'title' => $question->content,
                'options' => $options,
                'correct' => $correct,
                'answer' => $answer,
                'isCorrect' => $isCorrect
            ];
            $num++;
        }
        return ['items' => $final, 'totalCorrect' => $totalCorrect];
    }

    private function resolveOptions(Question $question)
    {
        $qt = $question->question_type;
        if ($qt == 1) return __('True') . "/" . __('False');
        if (in_array($qt, [2, 3, 5])) return $question->OptionsAsString();
        if ($qt == 4) return $question->PairsAsString();
    }

    private function resolveCorrect(Question $question)
    {
        $qt = $question->question_type;
        if ($qt == 1) return $question->isTrue ? __('True') : __('False');
        if (in_array($qt, [2, 3, 5])) return $question->CorrectOptionsAsString();
        if ($qt == 4) return $question->PairsAsString();
    }

    private function resolveAnswer(Question $question, $data)
    {
        $qt = $question->question_type;
        if ($qt == 1) return $data['answer'] ? __('True') : __('False');
        if ($qt == 2) return Options::findOne($data['answer'])->content;
        if ($qt == 5) return $data['answer'];
        return null;
    }

    private function getOptions($id, $res)
    {
        $data = [];
        foreach ($res as $d)
            if ($d['question'] == $id)
                $data[] = Options::findOne($d['answer'])->content;
        sort($data);
        return implode(', ', $data);
    }

    private function getPairs($id, $res)
    {
        $data = [];
        foreach ($res as $d)
            if ($d['question'] == $id)
                $data[] = $d['leftContent'] . " - " . $d['rightContent'];
        sort($data);
        return implode("\n", $data);
    }

    public static function addQuiz($id, $quiz, $cache = true)
    {
        if ($cache) $model = self::find()->where("quiz_id=$id AND active=1")->one();
        if (isset($model)) $model->quiz = $quiz;
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

    public static function getResult($id, $results)
    {
        foreach ($results as $result)
            if ($result['question'] == $id) return $result;
        return null;
    }

    /** After record is saved
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if ($this->results) {
            $model = QuizResults::find()->where(['quiz_id' => $this->quiz_id, 'temp_id' => $this->id, 'competitor_id' => Yii::$app->user->id])->one();
            if (!$model)
                $model = new QuizResults([
                    'quiz_id' => $this->quiz_id,
                    'temp_id' => $this->id,
                    'competitor_id' => Yii::$app->user->id
                ]);
            $model->results = $this->results;
            $model->save();
        }
    }
}
