<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\QuizCompetitors $model */

$this->title = 'Create Quiz Competitors';
$this->params['breadcrumbs'][] = ['label' => 'Quiz Competitors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="quiz-competitors-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
