<?php


use app\models\Question;
use app\widgets\CardView;
use kartik\select2\Select2;
use yii\bootstrap5\Html;

$options = Html::tag('div', $form->field($model, 'content')->textInput(['maxlength' => true]), ['class' => 'col-md-12']);
$options .= Html::tag('div', $form->field($model, 'question_type')->widget(Select2::class, [
  'data' => Question::QuestionTypes()
]), ['class' => 'col-md-4']);


echo  Html::tag('div', CardView::widget([
  'title' => null,
  'buttons' => [],
  'content' => Html::tag('div', $options, ['class' => 'row m-4'])
]), ['class' => 'col-md-12']);
