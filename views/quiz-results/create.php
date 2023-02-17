<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\QuizResults $model */

$this->title = 'Create Quiz Results';
$this->params['breadcrumbs'][] = ['label' => 'Quiz Results', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="quiz-results-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
