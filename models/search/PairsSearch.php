<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Pairs;

/**
 * PairsSearch represents the model behind the search form of `app\models\Pairs`.
 */
class PairsSearch extends Pairs
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'question_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['one', 'two'], 'safe'],
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
        $query = Pairs::find();

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
            'question_id' => $this->question_id,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'one', $this->one])
            ->andFilterWhere(['like', 'two', $this->two]);

        return $dataProvider;
    }
}
