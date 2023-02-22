<?php

use app\helpers\Buttons;
use app\models\Grade;
use app\widgets\CardView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\search\GradeSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Razredi';
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
            'title', [
                'class' => ActionColumn::class,
            ],
        ],
    ])
]);
