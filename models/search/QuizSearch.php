<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Quiz;

/**
 * QuizSearch represents the model behind the search form of `app\models\Quiz`.
 */
class QuizSearch extends Quiz
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'id', 'num_of_questions', 'duration', 'grade', 'level', 'status',
                'school_id', 'moderator_id', 'created_at', 'created_by', 'updated_at', 'updated_by',
                'quiz_type', 'activeNum'
            ], 'integer'],
            [['title'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Quiz::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'num_of_questions' => $this->num_of_questions,
            'duration' => $this->duration,
            'grade' => $this->grade,
            'level' => $this->level,
            'status' => $this->status,
            'quiz_type' => $this->quiz_type,
            'school_id' => $this->school_id,
            'moderator_id' => $this->moderator_id,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title]);
        if ($this->activeNum != null) {
            $activeSql = "SELECT quiz_id from quiz_temp where active=1 group by quiz_id having count(*)";
            $sql = "id  IN ($activeSql=$this->activeNum)";
            if ($this->activeNum == 0) $sql = "id NOT IN ($activeSql!=0)";
            $query->andWhere($sql);
        }

        return $dataProvider;
    }
}
