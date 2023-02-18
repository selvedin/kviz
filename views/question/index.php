<?php

use app\helpers\Buttons;
use app\models\Categories;
use app\models\Question;
use app\widgets\CardView;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\search\QuestionSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Questions';
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
            'content',
            ['attribute' => 'question_type', 'value' => function ($model) {
                return $model->questionType;
            }, 'filter' => Question::QuestionTypes()],
            ['attribute' => 'content_type', 'value' => function ($model) {
                return $model->contentType;
            }, 'filter' => Question::ContentTypes()],
            ['attribute' => 'category_id', 'value' => function ($model) {
                return $model->category?->name;
            }, 'filter' => Categories::getRoot()],
            ['attribute' => 'level', 'value' => function ($model) {
                return $model->levelLabel;
            }, 'filter' => Question::Levels()],
            ['attribute' => 'grade', 'value' => function ($model) {
                return $model->gradeLabel;
            }, 'filter' => Question::Grades()],
            ['attribute' => 'status', 'value' => function ($model) {
                return $model->statusLabel;
            }, 'filter' => Question::Statuses()],
            [
                'class' => ActionColumn::class,
            ],
        ],
    ])
]);
