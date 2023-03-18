<?php

use app\models\Categories;
use app\models\Grade;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

ActiveForm::begin(); ?>
<div class="row">
  <div class="col-6">
    <?= Html::label(__('Subject'), "subject", ['class' => 'control-label']) ?>
    <?= Html::dropDownList(
      "subject",
      $subject,
      [0 => __('Select Subject')] + Categories::getRoot(),
      ['class' => 'form-select', 'required' => true]
    ) ?>
  </div>
  <div class="col-6">
    <?= Html::label(__('Grade'), "grade", ['class' => 'control-label']) ?>
    <?= Html::dropDownList(
      "grade",
      $grade,
      [0 => __('Select Grade')] + Grade::list(),
      ['class' => 'form-select', 'required' => true]
    ) ?>
  </div>
  <div class="col-8">
    <?= Html::label(__('Unit title'), "title", ['class' => 'control-label']) ?>
    <?= Html::textInput("title", $title, ['class' => 'form-control']) ?>
  </div>
  <div class="col-4">
    <?= Html::label(__('Number of questions'), "num_of_questions", ['class' => 'control-label']) ?>
    <?= Html::dropDownList(
      "num_of_questions",
      $num_of_questions,
      array_combine($range, $range),
      ['class' => 'form-select', 'required' => true]
    ) ?>
  </div>
</div>
<div class="row mt-4">
  <div class="col-12 text-end">
    <?= Html::submitButton(__('Submit'), ['class' => 'btn btn-primary']) ?>
  </div>
</div>
<?php ActiveForm::end(); ?>