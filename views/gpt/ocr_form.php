<?php

use app\models\Categories;
use app\models\Grade;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use kartik\file\FileInput;

ActiveForm::begin(['id' => 'ocr-form', 'options' => ['enctype' => 'multipart/form-data']]); ?>
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
  <div class="col-6">
    <?= Html::label(__('Unit title'), "title", ['class' => 'control-label']) ?>
    <?= Html::textInput("title", $title, ['class' => 'form-control']) ?>
  </div>
  <div class="col-6">
    <?= Html::label(__('Lesson title'), "lesson", ['class' => 'control-label']) ?>
    <?= Html::textInput("lesson", $lesson, ['class' => 'form-control']) ?>
  </div>

  <div class="col-md-12 mt-2">
    <?= FileInput::widget([
      'name' => 'ocr',
      'options' => [
        'id' => 'ocr-file-input',
        'accept' => 'image/*',
        'multiple' => false,
      ],
      'pluginOptions' => [
        'previewFileType' => 'image',
        'showUpload' => false,
        'browseClass' => 'btn btn-success',
        'removeClass' => 'btn btn-danger',
      ]
    ]) ?>
  </div>
</div>
<div class="row mt-4">
  <div class="col-12 text-end">
    <?= Html::submitButton(__('Process'), ['class' => 'btn btn-primary']) ?>
  </div>
</div>
<?php ActiveForm::end(); ?>