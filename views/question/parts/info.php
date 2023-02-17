<?php


use app\models\Question;
use app\widgets\CardView;
use kartik\select2\Select2;
use yii\bootstrap5\Html;

$content = Html::tag('div', $form->field($model, 'content')->textInput(['maxlength' => true]), ['class' => 'col-md-12']);
$content .= Html::tag('div', $form->field($model, 'question_type')->widget(Select2::class, [
  'data' => Question::QuestionTypes()
]), ['class' => 'col-md-4']);
$content .= Html::tag('div', $form->field($model, 'content_type')->widget(Select2::class, [
  'data' => Question::ContentTypes()
]), ['class' => 'col-md-4']);
$content .= Html::tag('div', $form->field($model, 'category_id')->widget(Select2::class, [
  'data' => Question::Categories()
]), ['class' => 'col-md-4']);
$content .= Html::tag('div', $form->field($model, 'status')->widget(Select2::class, [
  'data' => Question::Statuses()
]), ['class' => 'col-md-4']);
$content .= Html::tag('div', $form->field($model, 'grade')->widget(Select2::class, [
  'data' => Question::Grades()
]), ['class' => 'col-md-4']);
$content .= Html::tag('div', $form->field($model, 'level')->widget(Select2::class, [
  'data' => Question::Levels()
]), ['class' => 'col-md-4']);


echo  Html::tag('div', CardView::widget([
  'title' => null,
  'buttons' => [],
  'content' => Html::tag('div', $content, ['class' => 'row m-4'])
]), ['class' => 'col-md-12']);
