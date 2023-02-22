<?php

use app\models\Categories;
use app\models\Grade;
use app\models\Question;
use app\widgets\CardView;
use kartik\select2\Select2;
use yii\bootstrap5\Html;

$content = Html::tag('div', $form->field($model, 'content')->textInput(['maxlength' => true]), ['class' => 'col-md-12']);
$content .= Html::tag('div', $form->field($model, 'question_type')->widget(Select2::class, [
  'data' => Question::QuestionTypes(),
  'options' => ['placeholder' => __('Select question type')],
  'pluginOptions' => [
    'allowClear' => true
  ],
  'pluginEvents' => [
    'change' => 'function(){mainApp.questionType=this.value;}',
  ]
]), ['class' => 'col-md-4']);

$content .= Html::tag('div', $form->field($model, 'content_type')->widget(
  Select2::class,
  [
    'data' => Question::ContentTypes(),
    'options' => ['placeholder' => __('Select content type')],
    'pluginOptions' => [
      'allowClear' => true
    ],
    'pluginEvents' => [
      'change' => 'function(){mainApp.contentType=this.value;}',
    ]
  ]
), ['class' => 'col-md-4']);
$content .= Html::tag('div', $form->field($model, 'category_id')->widget(
  Select2::class,
  [
    'data' => Categories::getRoot(),
    'options' => ['placeholder' => __('Select category')],
    'pluginOptions' => [
      'allowClear' => true
    ],
  ]
), ['class' => 'col-md-4']);
$content .= Html::tag('div', $form->field($model, 'status')->widget(
  Select2::class,
  [
    'data' => Question::Statuses(),
    'options' => ['placeholder' => __('Select status')],
    'pluginOptions' => [
      'allowClear' => true
    ],
  ]
), ['class' => 'col-md-4']);
$content .= Html::tag('div', $form->field($model, 'grade')->widget(
  Select2::class,
  [
    'data' => Grade::list(),
    'options' => ['placeholder' => __('Select grade')],
    'pluginOptions' => [
      'allowClear' => true
    ],
  ]
), ['class' => 'col-md-4']);
$content .= Html::tag('div', $form->field($model, 'level')->widget(
  Select2::class,
  [
    'data' => Question::Levels(),
    'options' => ['placeholder' => __('Select level')],
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
