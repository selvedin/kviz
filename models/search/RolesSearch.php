<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Roles;

/**
 * RolesSearch represents the model behind the search form of `app\models\Roles`.
 */
class RolesSearch extends Roles
{
    public $isPrivate;
    public $createdBy;
    public $updatedBy;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_role', 'private', 'created_by', 'updated_by'], 'integer'],
            [[
                'name', 'description', 'created_at', 'updated_at',
                'isPrivate', 'createdBy', 'updatedBy'
            ], 'safe'],
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
        $query = Roles::find();
        $query->joinWith(['createdBy']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['createdBy'] = [
            'asc' => ['user.username' => SORT_ASC],
            'desc' => ['user.username' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['isPrivate'] = [
            'asc' => ['private' => SORT_ASC],
            'desc' => ['private' => SORT_DESC],
        ];
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_role' => $this->id_role,
            'private' => $this->isPrivate,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'created_at', $this->created_at])
            ->andFilterWhere(['like', 'user.username', $this->createdBy])
            ->andFilterWhere(['like', 'updated_at', $this->updated_at]);

        return $dataProvider;
    }
}
