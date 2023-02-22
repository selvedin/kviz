<?php

use app\widgets\CardView;
use yii\bootstrap5\Html;

$tfs = Html::tag(
  'div',
  Html::label(__('True')) .
    Html::tag(
      'div',
      Html::label(__('True'), 'tf-is_true', ['class' => 'control-label']) .
        Html::radio(
          'Question[Options][is_true]',
          1,
          ['value' => 1, 'id' => 'tf-is_true', 'class' => 'form-check-input']
        ),
      ['class' => 'form-check form-switch field-options-is_true mt-1']
    ),
  ['class' => 'col-sm-6 col-md-2']
);
$tfs .= Html::tag(
  'div',
  Html::label(__('False')) .
    Html::tag(
      'div',
      Html::label(__('False'), 'tf-is_true', ['class' => 'control-label']) .
        Html::radio(
          'Question[Options][is_true]',
          0,
          ['value' => 0, 'id' => 'tf-is_false', 'class' => 'form-check-input']
        ),
      ['class' => 'form-check form-switch field-options-is_false mt-1']
    ),
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
