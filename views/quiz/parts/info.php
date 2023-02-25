<?php

use app\models\Grade;
use app\models\Question;
use app\models\Quiz;
use app\models\User;
use app\widgets\CardView;
use kartik\select2\Select2;
use yii\bootstrap5\Html;

$content = Html::tag('div', $form->field($model, 'title')->textInput(['maxlength' => true]), ['class' => 'col-md-12']);
$content .= Html::tag('div', $form->field($model, 'num_of_questions')->textInput(['type' => 'number', 'min' => 1, 'maxlength' => true]), ['class' => 'col-md-4']);
$content .= Html::tag('div', $form->field($model, 'duration')->textInput(['type' => 'number', 'min' => 1, 'maxlength' => true]), ['class' => 'col-md-4']);
$content .= Html::tag('div', $form->field($model, 'moderator_id')->widget(Select2::class, [
  'data' => User::list(),
  'options' => ['placeholder' => __('Select a moderator')],
  'pluginOptions' => [
    'allowClear' => true
  ]
]), ['class' => 'col-md-4']);

$content .= Html::tag('div', $form->field($model, 'grade')->widget(
  Select2::class,
  [
    'data' => Grade::list(),
    'options' => ['placeholder' => __('Select a grade')],
    'pluginOptions' => [
      'allowClear' => true
    ],
  ]
), ['class' => 'col-md-4']);
$content .= Html::tag('div', $form->field($model, 'level')->widget(
  Select2::class,
  [
    'data' => Question::Levels(),
    'options' => ['placeholder' => __('Select a level')],
    'pluginOptions' => [
      'allowClear' => true
    ],
  ]
), ['class' => 'col-md-4']);

$content .= Html::tag('div', $form->field($model, 'quiz_type')->widget(
  Select2::class,
  [
    'data' => Quiz::getTypes(),
    'options' => ['placeholder' => __('Select a type')],
    'pluginOptions' => [
      'allowClear' => true
    ],
  ]
), ['class' => 'col-md-4']);


echo  Html::tag('div', CardView::widget([
  'title' => null,
  'buttons' => [],
  'content' => Html::tag('div', $content, ['class' => 'row m-4'])
]), ['class' => 'col-md-12']);
