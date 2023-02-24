<?php

namespace app\models;

use app\helpers\CacheHelper;
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
        $CACHE_KEY = "_temp_quiz_results__$this->id" . Yii::$app->user->id;
        $cache = CacheHelper::get($CACHE_KEY);
        if ($cache == false) {
            $id = $id ? $id : Yii::$app->user->id;
            $final = [];
            $quiz = unserialize($this->quiz);
            $results = unserialize($this->results);
            $results = unserialize($results[$id]);
            $results = $results['results'];
            $processed = [];
            $num = 1;
            $totalCorrect = 0;
            foreach ($quiz as $q) {
                //TODO - if there is no question in results it has no answer - process
                $question = Question::findOne($q['id']);
                $result = $this->extractResult($results, $q['id']);
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
            QuizResults::add($this, $final, $totalCorrect);
            $cache = ['items' => $final, 'totalCorrect' => $totalCorrect];
            CacheHelper::set($CACHE_KEY, $cache);
        }
        return $cache;
    }

    private function extractResult($results, $q)
    {
        foreach ($results as $result) if ($result['question'] == $q) return $result;
        return null;
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
        if ($qt == 1) return isset($data['answer']) ? ($data['answer'] ? __('True') : __('False')) : __("No Answer");
        if ($qt == 2) return isset($data['answer']) ? Options::findOne($data['answer'])->content : __("No Answer");
        if ($qt == 5) return isset($data['answer']) ? $data['answer'] : __("No Answer");
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
    }
}
