<?php

use app\helpers\Buttons;
use app\widgets\CardView;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Roles $model */
/** @var yii\widgets\ActiveForm $form */
$this->title = __('Role');
?>

<?php $form = ActiveForm::begin(); ?>
<?= CardView::begin([
    'title' => $this->title,
    'type' => 'info',
    'buttons' => [
        Buttons::List(),
        $model->isNewRecord ? '' : Buttons::View('id', $model->id_role),
        Buttons::Save()
    ],
]) ?>

<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

<?= $form->field($model, 'private')->checkbox() ?>

<?= CardView::end() ?>
<?php ActiveForm::end(); ?>