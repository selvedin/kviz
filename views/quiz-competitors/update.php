<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\QuizCompetitors $model */

$this->title = 'Update Quiz Competitors: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Quiz Competitors', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="quiz-competitors-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
