<?php

use app\helpers\Buttons;
use app\widgets\CardView;
use yii\widgets\DetailView;

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => __($modelName), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<?= CardView::widget([
    'title' => $this->title,
    'buttons' => [Buttons::List(), __isUser(Buttons::Update('id', $model->id))],
    'content' => DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
        ],
    ])
]) ?>