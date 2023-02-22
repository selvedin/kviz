<?php

use app\helpers\Icons;
use app\widgets\CardView;
use app\widgets\SwitchInput;
use yii\bootstrap5\Html;

$optionLabel = __('Option');
$trueLabel = __('True');
$yesIcon = Icons::faIcon('check text-success');
$noIcon = Icons::faIcon('times text-danger');

$options = Html::tag('div', Html::label($optionLabel, 'options-content', ['class' => 'control-label']) .
  Html::textInput('Options[content]', '', ['id' => 'options-content', 'class' => 'form-control']), ['class' => 'col-sm-12 col-md-8 col-lg-10']);
$options .= Html::tag(
  'div',
  SwitchInput::widget([
    'name' => 'Options[is_true]',
    'label' => $trueLabel,
    'value' => 1,
    'checked' => false,
    'id' => 'options-is_true'
  ]),
  ['class' => 'col-sm-6 col-md-2 col-lg-1 pt-4']
);

$options .= Html::tag('div', Html::a(
  Icons::faIcon('plus'),
  'javascript:void(0)',
  ['class' => 'btn btn-primary', '@click' => 'addOption()']
), ['class' => 'col-sm-6 col-md-2 col-lg-1 text-end mt-4']);

$options .= Html::tag(
  'div',
  "<table class='table table-striped'><thead><tr><th>#</th><th>$optionLabel</th><th>$trueLabel</th><th></th></tr></thead>
  <tbody><tr v-for='(opt,ind) in options'>
  <td>
  <input type='hidden' :name='\"Question[Options][\"+ind+\"][id]\"' :value='opt.id' readonly/>
  <input type='hidden' :name='\"Question[Options][\"+ind+\"][content]\"' :value='opt.content' readonly/>
  <input type='hidden' :name='\"Question[Options][\"+ind+\"][is_true]\"' :value='+opt.is_true' readonly/>
  {{ind+1}}.</td>
  <td>{{opt.content}}</td>
  <td><i v-if='opt.is_true' class='fas fa-check text-success'></i><i v-else class='fas fa-times text-danger'></i></td>
  <td class='text-end'><a href='javascript:void(0)' class='btn rounded-pill btn-icon btn-outline-danger btn-sm' @click='removeOption(opt.id)'>
  <i class='fa fa-trash'></i></a></td>
  </tr>
  </tbody></table></div></div>",
  ['id' => 'optionsList', 'class' => 'col-md-12 mt-4']
);

echo  Html::tag('div', CardView::widget([
  'title' => $optionLabel,
  'buttons' => [],
  'content' => Html::tag('div', $options, ['class' => 'row m-4'])
]), array_merge(['class' => 'col-md-12 questionFormPart'], ['id' => 'question-options-card', 'v-if' => '[2,3].includes(+questionType)']));
