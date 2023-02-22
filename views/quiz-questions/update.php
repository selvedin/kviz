<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\QuizQuestions $model */

$this->title = 'Update Quiz Questions: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Quiz Questions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="quiz-questions-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
