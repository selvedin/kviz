<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\QuizQuestions $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="quiz-questions-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'quiz_id')->textInput() ?>

    <?= $form->field($model, 'question_id')->textInput() ?>

    <?= $form->field($model, 'duration')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'updated_by')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
