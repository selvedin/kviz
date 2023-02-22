<?php

use app\models\QuizQuestions;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\search\QuizQuestionsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Quiz Questions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="quiz-questions-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Quiz Questions', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'quiz_id',
            'question_id',
            'duration',
            'created_at',
            //'created_by',
            //'updated_at',
            //'updated_by',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, QuizQuestions $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
