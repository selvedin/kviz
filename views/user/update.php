<?php

use app\helpers\Buttons;
use app\models\Roles;
use app\models\User;
use app\widgets\CardView;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\User $model */

$this->title = __('Update') . ' ' . $model->name;
?>
<div class="row">
    <div class="col-12">
        <?php $form = ActiveForm::begin(); ?>
        <?= CardView::begin([
            'title' => $this->title,
            'buttons' => [Buttons::List(), $model->isNewRecord ? '' : Buttons::View('id', $model->id), Buttons::Save()]
        ]) ?>

        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'first_name')->textInput() ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'last_name')->textInput() ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'status')->dropdownList(User::getStatuses()) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'role_id')->dropdownList(Roles::getNames()) ?>
            </div>
        </div>
        <?= CardView::end() ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>