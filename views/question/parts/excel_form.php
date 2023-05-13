<?php

use app\models\Categories;
use app\models\Grade;
use app\models\Question;
use yii\bootstrap5\Html;
?>
<div class="row">
  <input type="hidden" id="createQuestion" name="createQuestion" value="0">
  <input type="hidden" id="fileName" name="file_name" value="<?= $model['file_name'] ?>">
  <div class="col-md-6 pt-2">
    <?= Html::label(__('Subject'), "subject", ['class' => 'control-label']) ?>
    <?= Html::dropDownList(
      "subject",
      $model['subject'],
      [0 => __('Select Subject')] + Categories::getRoot(),
      ['class' => 'form-select']
    ) ?>
  </div>
  <div class="col-md-6 pt-2">
    <?= Html::label(__('Grade'), "grade", ['class' => 'control-label']) ?>
    <?= Html::dropDownList(
      "grade",
      $model['grade'],
      [0 => __('Select Grade')] + Grade::list(),
      ['class' => 'form-select']
    ) ?>
  </div>
  <div class="col-md-6 pt-2">
    <?= Html::label(__('Level'), "level", ['class' => 'control-label']) ?>
    <?= Html::dropDownList(
      "level",
      $model['level'],
      [0 => __('Select Level')] + Question::Levels(),
      ['class' => 'form-select']
    ) ?>
  </div>
  <div class="col-md-6 pt-2">
    <?= Html::label(__('Question Type'), "questionType", ['class' => 'control-label']) ?>
    <?= Html::dropDownList(
      "questionType",
      $model['questionType'],
      Question::QuestionTypes(),
      ['class' => 'form-select', 'required' => true]
    ) ?>
  </div>
  <div class="col-md-3 pt-2">
    <?= Html::label(__('Number of options'), "numberOfOptions", ['class' => 'control-label']) ?>
    <?= Html::textInput("numberOfOptions",  $model['numberOfOptions'], ['class' => 'form-control', 'type' => 'number', 'min' => 2]) ?>
  </div>
  <div class="col-md-3 pt-2">
    <?= Html::label(__('Question duration'), "duration", ['class' => 'control-label']) ?>
    <?= Html::textInput("duration",  $model['duration'], ['class' => 'form-control', 'type' => 'number', 'min' => 2]) ?>
  </div>
  <div class="col-md-6 pt-2">
    <?= Html::label(__('Excel File'), "excelFile", ['class' => 'control-label']) ?>
    <?= Html::fileInput("excelFile", '', [
      'class' => 'form-control',
      'required' => true,
      'accept' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
    ]) ?>
  </div>
</div>