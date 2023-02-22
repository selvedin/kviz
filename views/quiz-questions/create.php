<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\QuizQuestions $model */

$this->title = 'Create Quiz Questions';
$this->params['breadcrumbs'][] = ['label' => 'Quiz Questions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="quiz-questions-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
