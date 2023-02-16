<?php

use app\helpers\Buttons;
use app\widgets\CardView;
use yii\grid\ActionColumn;
use yii\grid\GridView;

$this->title = __($modelName);
$this->params['breadcrumbs'][] = $this->title;
?>
<?= CardView::widget([
    'title' => $this->title,
    'buttons' => [Buttons::Create()],
    'content' => GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'title',
            ['class' => ActionColumn::class],
        ],
    ])
]) ?>