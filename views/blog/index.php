<?php

use app\helpers\Buttons;
use app\widgets\CardView;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\search\BlogSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = __('Blog');
?>

<?= CardView::widget([
    'marginless' => true,
    'title' => $this->title,
    'buttons' => [Buttons::Create(), Buttons::ResetList()],
    'content' => GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-striped'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'title',
            'author',
            [
                'class' => ActionColumn::class,
            ],
        ],
    ])
]);
