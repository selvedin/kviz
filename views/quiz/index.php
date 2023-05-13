<?php

use app\helpers\Buttons;
use app\helpers\Icons;
use app\models\Question;
use app\models\Quiz;
use app\models\QuizTemp;
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
        'buttons' => [
            Buttons::Create(),
            Buttons::customButton(Icons::faIcon('file-excel me-2') . ' ' . __('Create from Excel'), ['create-from-excel'], [], 'success'),
            Buttons::ResetList()
        ],
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
                    return $model->gradeLabel?->title;
                }, 'filter' => Question::Grades()],
                ['attribute' => 'quiz_type', 'value' => function ($model) {
                    return $model->quizType;
                }, 'filter' => Quiz::getTypes()],
                ['attribute' => 'activeNum', 'value' => function ($model) {
                    return QuizTemp::find()->where(['active' => 1, 'quiz_id' => $model->id])->count();
                }, 'options' => ['style' => 'width: 10%;']],
                [
                    'class' => ActionColumn::class,
                ],
            ],
        ])
    ]);
    ?>
</div>