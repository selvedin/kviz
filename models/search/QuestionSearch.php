<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Question;

/**
 * QuestionSearch represents the model behind the search form of `app\models\Question`.
 */
class QuestionSearch extends Question
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'id', 'question_type', 'content_type', 'status', 'grade', 'level',
                'created_at', 'created_by', 'updated_at', 'updated_by'
            ], 'integer'],
            [['content', 'category_id'], 'safe'],
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
        $query = Question::find();

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
        $category = $this->category_id;
        if (is_array(Yii::$app->user->identity->subjectList))
            $category = $this->category_id ? $this->category_id : Yii::$app->user->identity->subjectList;
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'question_type' => $this->question_type,
            'content_type' => $this->content_type,
            'status' => $this->status,
            'grade' => $this->grade,
            'level' => $this->level,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['IN', 'category_id', $category]);

        return $dataProvider;
    }
}
