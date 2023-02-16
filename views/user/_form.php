<?php

use app\helpers\Buttons;
use app\widgets\CardView;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\models\User $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="row">
    <div class="col-12">
        <?php $form = ActiveForm::begin(); ?>
        <?= CardView::begin([
            'title' => __('Create user'),
            'buttons' => [Buttons::List(), Buttons::Save()]
        ]) ?>

        <div class="row">
            <?= Html::tag('div', $form->field($model, 'first_name')->textInput(), ['class' => 'col-md-6']) ?>
            <?= Html::tag('div', $form->field($model, 'last_name')->textInput(), ['class' => 'col-md-6']) ?>
            <?= Html::tag('div', $form->field($model, 'email')->textInput(), ['class' => 'col-md-6']) ?>
            <?= Html::tag('div', $form->field($model, 'username')->textInput(['autocomplete' => 'off']), ['class' => 'col-md-6']) ?>
            <?= Html::tag('div', $form->field($model, 'password')->passwordInput(['autocomplete' => 'off']), ['class' => 'col-md-6']) ?>

            <?= Html::tag('div', $form->field($model, 'password_repeat')->passwordInput(['autocomplete' => 'off']), ['class' => 'col-md-6']) ?>
        </div>
        <?= CardView::end() ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>