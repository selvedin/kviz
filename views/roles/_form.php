<?php

use app\helpers\Buttons;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Roles $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="row">
    <div class="col-12">
        <?php $form = ActiveForm::begin(); ?>

        <div class="card card-secondary">
            <div class="card-header text-right">
                <?= Buttons::List() ?>
                <?= $model->isNewRecord ? '' : Buttons::View('id', $model->id_role) ?>
                <?= Buttons::Save() ?>
            </div>
            <div class="card-body">

                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

                <?= $form->field($model, 'private')->checkbox() ?>

            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>