<?php

use app\helpers\Buttons;
use app\helpers\Icons;
use app\models\Categories;
use app\models\Grade;
use app\models\Perms;
use app\widgets\CardView;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\models\Question $model */

$this->title = __('Generating Questions');
\yii\web\YiiAsset::register($this);
$perms = new Perms();
$range = range(1, 10);
$check = Icons::Correct('');
?>
<div id="quizApp">
  <?= CardView::begin([
    'title' => $this->title,
    'type' => 'info',
    'buttons' => [Buttons::List()],
  ]) ?>
  <br />
  <div class="row">
    <div class="col-xl-6 col-md-6 mb-4">
      <div class="card h-100">
        <div class="card-header d-flex justify-content-between">
          <div class="card-title mb-0">
            <h5 class="mb-0"><?= $this->title ?></h5>
            <small class="text-muted"><?= __('Title') ?></small>
          </div>
        </div>
        <div class="card-body">
          <?php ActiveForm::begin(); ?>
          <div class="row">
            <div class="col-6">
              <?= Html::label(__('Subject'), "subject", ['class' => 'control-label']) ?>
              <?= Html::dropDownList("subject", $subject, Categories::getRoot(), ['class' => 'form-select']) ?>
            </div>
            <div class="col-6">
              <?= Html::label(__('Grade'), "grade", ['class' => 'control-label']) ?>
              <?= Html::dropDownList("grade", $grade, Grade::list(), ['class' => 'form-select']) ?>
            </div>
            <div class="col-8">
              <?= Html::label(__('Unit title'), "title", ['class' => 'control-label']) ?>
              <?= Html::textInput("title", $title, ['class' => 'form-control']) ?>
            </div>
            <div class="col-4">
              <?= Html::label(__('Number of questions'), "num_of_questions", ['class' => 'control-label']) ?>
              <?= Html::dropDownList("num_of_questions", $num_of_questions, array_combine($range, $range), ['class' => 'form-select']) ?>
            </div>
          </div>
          <div class="row mt-4">
            <div class="col-12 text-end">
              <?= Html::submitButton(__('Submit'), ['class' => 'btn btn-primary']) ?>
            </div>
          </div>
          <?php ActiveForm::end(); ?>
        </div>
      </div>
    </div>
    <div class="col-xl-6 col-md-6 mb-4">
      <div class="card h-100">
        <div class="card-header d-flex justify-content-between">
          <div class="card-title mb-0">
            <h5 class="mb-0"><?= __('Results') ?></h5>
          </div>
        </div>
        <div class="card-body">
          <?php
          if (is_array($response))
            foreach ($response as $question) {
              $parts = explode("\n", $question);
              foreach ($parts as $k => $part) {
                if ($k == 0)
                  echo Html::tag('div', Html::tag('strong', $part));
                else {
                  $part = str_replace('[x]', $check, $part);
                  $part = str_replace('[', '', $part);
                  $part = str_replace(']', '', $part);
                  echo Html::tag('div', $part);
                }
              }
              echo Html::tag('hr');
            }
          ?>

        </div>
      </div>
    </div>

  </div>
  <?= CardView::end(); ?>
</div>