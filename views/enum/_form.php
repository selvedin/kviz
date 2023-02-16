<?php

use app\helpers\Buttons;
use app\widgets\CardView;
use yii\widgets\ActiveForm;

$this->title = $model->isNewRecord ? __('Add') . ' - ' . __($modelName) : $model->title;
?>
<?php $form = ActiveForm::begin(); ?>
<?= CardView::widget([
    'title' => $this->title,
    'buttons' => [Buttons::Save(), Buttons::List()],
    'content' => $form->field($model, 'title')->textInput(['maxlength' => true])
]) ?>
<?php ActiveForm::end(); ?>