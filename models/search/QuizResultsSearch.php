<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\QuizResults;

/**
 * QuizResultsSearch represents the model behind the search form of `app\models\QuizResults`.
 */
class QuizResultsSearch extends QuizResults
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'quiz_id', 'question_id', 'temp_id', 'answer_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['results'], 'safe'],
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
        $query = QuizResults::find();

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
            'quiz_id' => $this->quiz_id,
            'temp_id' => $this->question_id,
            'competitor_id' => $this->competitor_id,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);
        $query->andFilterWhere(['like', 'results', $this->results])
            ->andFilterWhere(['like', 'summary', $this->summary])
            ->andFilterWhere(['like', 'totals', $this->totals]);

        return $dataProvider;
    }
}
