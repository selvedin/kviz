<?php

use app\helpers\Buttons;
use app\widgets\CardView;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Grade $model */

$this->title = $model->title;
\yii\web\YiiAsset::register($this);

echo CardView::widget([
    'title' => $this->title,
    'buttons' => [
        Buttons::List(),
        Buttons::Update('id', $model->id),
        Buttons::Delete('id', $model->id),

    ],
    'content' => DetailView::widget([
        'model' => $model,
        'attributes' => [
            'title',
        ],
    ])
]);
