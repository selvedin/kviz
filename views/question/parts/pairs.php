i<?php

  use app\helpers\Icons;
  use app\widgets\CardView;
  use yii\bootstrap5\Html;

  $oneLabel = __('Question');
  $twoLabel = __('Pair');

  $pairs = Html::tag(
    'div',
    Html::label($oneLabel, 'pairs-one', ['class' => 'control-label']) .
      Html::textInput('Pairs[one]', '', ['id' => 'pairs-one', 'class' => 'form-control']),
    ['class' => 'col-sm-12 col-md-5']
  );

  $pairs .= Html::tag(
    'div',
    Html::label($twoLabel, 'pairs-two', ['class' => 'control-label']) .
      Html::textInput('Pairs[two]', '', ['id' => 'pairs-two', 'class' => 'form-control']),
    ['class' => 'col-sm-12 col-md-5']
  );


  $pairs .= Html::tag('div', Html::a(
    Icons::faIcon('plus'),
    'javascript:void(0)',
    ['class' => 'btn btn-primary', '@click' => 'addOption(true)']
  ), ['class' => 'col-sm-12 col-md-2 text-end mt-4']);

  $pairs .= Html::tag(
    'div',
    "<table class='table table-striped'><thead><tr><th>#</th><th>$oneLabel</th><th>$twoLabel</th><th></th></tr></thead>
  <tbody><tr v-for='(opt,ind) in pairs'>
  <td>
  <input type='hidden' :name='\"Question[Pairs][\"+ind+\"][id]\"' :value='opt.id' readonly/>
  <input type='hidden' :name='\"Question[Pairs][\"+ind+\"][one]\"' :value='opt.one' readonly/>
  <input type='hidden' :name='\"Question[Pairs][\"+ind+\"][two]\"' :value='opt.two' readonly/>
  {{ind+1}}.</td>
  <td>{{opt.one}}</td>
  <td>{{opt.two}}</td>
  <td class='text-end'><a href='javascript:void(0)' class='btn rounded-pill btn-icon btn-outline-danger btn-sm' @click='removeOption(opt.id, true)'>
  <i class='fa fa-trash'></i></a></td>
  </tr>
  </tbody></table></div></div>",
    ['id' => 'pairsList', 'class' => 'col-md-12 mt-4']
  );

  echo  Html::tag('div', CardView::widget([
    'title' => __('Pairs'),
    'buttons' => [],
    'content' => Html::tag('div', $pairs, ['class' => 'row m-4'])
  ]), array_merge(['class' => 'col-md-12 questionFormPart'], ['id' => 'question-pairs-card', 'v-if' => 'questionType == 4']));
