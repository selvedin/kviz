<?php

use app\helpers\Buttons;
use app\widgets\CardView;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Roles $model */

$this->title = $model->name;
\yii\web\YiiAsset::register($this);
echo CardView::widget([
    'title' => $model->name . ' - ' . __('Role'),
    'buttons' => [
        Buttons::List(),
        Buttons::Create(),
        Buttons::Update('id', $model->id_role),
        Buttons::Delete('id', $model->id_role),

    ],
    'content' => DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            ['attribute' => 'isPrivate', 'label' => __('Private')],
            'description',
        ],
    ])
]);
