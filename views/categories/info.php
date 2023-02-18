<?php

use app\widgets\CardView;
use yii\bootstrap5\Html;

$content = Html::tag('div', $form->field($model, 'name')->textInput(['maxlength' => true]), ['class' => 'col-md-6']);
$content .= Html::tag('div', $form->field($model, 'color')->textInput(['type' => 'color']), ['class' => 'col-md-3']);
$content .= Html::tag('div', $form->field($model, 'icon')->textInput(['maxlength' => true]), ['class' => 'col-md-3']);

echo  Html::tag('div', CardView::widget([
  'title' => null,
  'buttons' => [],
  'content' => Html::tag('div', $content, ['class' => 'row m-4'])
]), ['class' => 'col-md-12']);
