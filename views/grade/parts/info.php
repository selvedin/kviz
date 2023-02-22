<?php

use app\widgets\CardView;
use yii\bootstrap5\Html;

$content = Html::tag('div', $form->field($model, 'title')->textInput(['maxlength' => true]), ['class' => 'col-md-12']);

echo  Html::tag('div', CardView::widget([
  'title' => null,
  'buttons' => [],
  'content' => Html::tag('div', $content, ['class' => 'row m-4'])
]), ['class' => 'col-md-12']);
