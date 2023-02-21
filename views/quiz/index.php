<?php

use app\helpers\Buttons;
use app\models\Question;
use app\widgets\CardView;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\search\QuestionSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = __('Quizes');
?>
<div id='quizApp'>
    <?= CardView::widget([
        'title' => $this->title,
        'buttons' => [Buttons::Create(), Buttons::ResetList()],
        'content' => GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'tableOptions' => ['class' => 'table table-striped'],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'title',
                'num_of_questions',
                'duration',
                ['attribute' => 'level', 'value' => function ($model) {
                    return $model->levelLabel;
                }, 'filter' => Question::Levels()],
                ['attribute' => 'grade', 'value' => function ($model) {
                    return $model->gradeLabel;
                }, 'filter' => Question::Grades()],
                [
                    'class' => ActionColumn::class,
                ],
            ],
        ])
    ]);
    ?>
</div>