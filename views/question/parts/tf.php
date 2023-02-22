<?php

use app\widgets\CardView;
use app\widgets\SwitchInput;
use yii\bootstrap5\Html;

echo "<h1>$model->firstOption</h1>";
$tfs = Html::tag(
  'div',
  SwitchInput::widget([
    'name' => 'Question[Options][is_true]',
    'label' => __('True'),
    'value' => 1,
    'checked' => $model->isTrue,
    'id' => 'tf-is_true',
    'isRadio' => true
  ]),
  ['class' => 'col-sm-6 col-md-2']
);
$tfs .= Html::tag(
  'div',
  SwitchInput::widget([
    'name' => 'Question[Options][is_true]',
    'label' => __('False'),
    'value' => 0,
    'checked' => !$model->isTrue,
    'id' => 'tf-is_false',
    'isRadio' => true
  ]),
  ['class' => 'col-sm-6 col-md-2']
);

echo  Html::tag(
  'div',
  CardView::widget([
    'title' => __('True/False'),
    'buttons' => [],
    'content' => Html::tag('div', $tfs, ['class' => 'row m-4'])
  ]),
  [
    'class' => 'col-md-12 questionFormPart',
    'id' => 'question-tf-card',
    'v-if' => 'questionType == 1'
  ]
);
