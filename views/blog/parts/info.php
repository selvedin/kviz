<?php

use app\models\Blog;
use app\widgets\CardView;
use yii\bootstrap5\Html;

$content =  $form->field($model, 'status')->hiddenInput(['maxlength' => true])->label(false);
$content .=  $form->field($model, 'author')->hiddenInput(['maxlength' => true])->label(false);
$content .= Html::tag('div', $form->field($model, 'title')->textInput(['maxlength' => true]), ['class' => 'col-md-12']);
$content .= Html::tag('div', Html::label(__('Status', ['class' => 'font-bold'])) . ' '
  . Html::label(Blog::getStatuses()[$model->status]), ['class' => 'col-md-12 mt-4']);
$content .= Html::tag('div', Html::label(__('Author', ['class' => 'fnot-bold'])) . ' '
  . Html::label($model->author), ['class' => 'col-md-12 mt-4']);


echo  Html::tag('div', CardView::widget([
  'title' => __('Info'),
  'buttons' => [],
  'content' => Html::tag('div', $content, ['class' => 'row'])
]), ['class' => 'col-md-12']);
