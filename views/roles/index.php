<?php

use app\helpers\Buttons;
use app\widgets\CardView;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\search\RolesSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = __('Roles');
?>

<?= CardView::widget([
    'title' => $this->title,
    'buttons' => [Buttons::Create(), Buttons::ResetList()],
    'content' => GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-striped'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            ['attribute' => 'isPrivate', 'label' => __('Private'), 'filter' => [__('No'), __('Yes')]],
            'description',
            ['attribute' => 'createdBy', 'label' => __('Created by'), 'value' => function ($model) {
                return $model->createdBy?->name;
            }],
            [
                'class' => ActionColumn::class,
            ],
        ],
    ])
]);
