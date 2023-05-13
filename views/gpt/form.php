<?php

use app\helpers\Icons;
use app\models\Categories;
use app\models\Grade;
use app\models\Question;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

ActiveForm::begin(); ?>
<div class="row">
  <div class="col-md-6 pt-2">
    <?= Html::label(__('Subject'), "subject", ['class' => 'control-label']) ?>
    <?= Html::dropDownList(
      "subject",
      $model->subject,
      [0 => __('Select Subject')] + Categories::getRoot(),
      ['class' => 'form-select', 'required' => true, 'v-model' => 'subject']
    ) ?>
  </div>
  <div class="col-md-6 pt-2">
    <?= Html::label(__('Grade'), "grade", ['class' => 'control-label']) ?>
    <?= Html::dropDownList(
      "grade",
      $model->grade,
      [0 => __('Select Grade')] + Grade::list(),
      ['class' => 'form-select', 'required' => true, 'v-model' => 'grade']
    ) ?>
  </div>
  <div class="col-md-6 pt-2">
    <?= Html::label(__('Question Type'), "questionType", ['class' => 'control-label']) ?>
    <?= Html::dropDownList(
      "questionType",
      $model->questionType,
      [
        Question::TYPE_ESSAI => __('Essei'),
        Question::TYPE_SINGLE => __('Single Choice'),
        Question::TYPE_MULTI => __('Multiple Choice'),
        Question::TYPE_TRUE => __('True'), ' / ' . __('False'),
        Question::TYPE_PROMPT => __('Text'),
      ],
      ['class' => 'form-select', 'required' => true, 'v-model' => 'questionType']
    ) ?>
  </div>
  <div class="col-md-6 pt-2">
    <?= Html::label(__('Number of questions'), "num_of_questions", ['class' => 'control-label']) ?>
    <?= Html::dropDownList(
      "num_of_questions",
      $model->num_of_questions,
      array_combine($range, $range),
      ['class' => 'form-select', 'required' => true]
    ) ?>
  </div>

  <div class="col-md-12 pt-2">
    <?= Html::label(__('Unit title'), "title", ['class' => 'control-label']) ?>
    <?= Html::textInput("title", $model->title, ['class' => 'form-control']) ?>
  </div>
  <div v-if="questionType == <?= Question::TYPE_PROMPT ?>" class="col-md-12 pt-2">
    <?= Html::label(__('Text'), "prompt", ['class' => 'control-label']) ?>
    <?= Html::textarea("prompt", $model->prompt, ['class' => 'form-control', 'rows' => 6, 'v-model' => 'prompt']) ?>
    <div class="row mt-2">
      <div class="col-12">
        <?= Html::fileInput('image', null, ['accept' => 'image/*', 'class' => 'd-none', 'id' => 'scan-image', '@change' => "scanImage()"]) ?>
      </div>
      <div class="col-12 text-end">
        <?= Html::button(
          Html::tag('span', Icons::faIcon('x-ray'), ['v-if' => '!isUploading'])
            . Html::tag('span', Icons::faIcon('cog fa-spin'), ['v-if' => 'isUploading'])
            . Html::tag('span', __('Scan image'), ['class' => 'ms-2']),
          ['class' => 'btn btn-success', 'onclick' => "$('#scan-image').click();", ':disabled' => 'isUploading']
        ) ?>
      </div>
    </div>
  </div>
  <div class="row mt-4 pt-4 border-top">
    <div class="col-12 text-end">
      <?= Html::submitButton(__('Generate'), ['class' => 'btn btn-primary', ':disabled' => '!canGenerate']) ?>
    </div>
  </div>
</div>
<?php ActiveForm::end(); ?>