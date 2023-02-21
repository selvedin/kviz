<?php

use app\widgets\CardView;
use yii\bootstrap5\Html;

$tfs = Html::tag(
  'div',
  Html::label(__('Result'), 'input-content', ['class' => 'control-label']) .
    Html::textInput(
      'Question[Options][0][content]',
      '',
      ['id' => 'input-content', 'class' => 'form-control']
    ),
  ['class' => 'col-sm-6 col-md-2']
);

echo  Html::tag(
  'div',
  CardView::widget([
    'title' => __('Input result'),
    'buttons' => [],
    'content' => Html::tag('div', $tfs, ['class' => 'row m-4'])
  ]),
  [
    'class' => 'col-md-12 questionFormPart',
    'id' => 'question-input-card',
    'v-if' => 'questionType == 5'
  ]
);
