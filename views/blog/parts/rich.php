<?php

use app\widgets\AccordionCard;
use yii\bootstrap5\Html;
use kartik\editors\Summernote;

echo Html::tag('div', AccordionCard::Widget([
  'title' => ($model->content ? "<i class='fas fa-check-circle text-success mx-2'></i>" : '') . __('Content'),
  'id' => 'blogContent',
  'content' => $form->field($model, 'content')->widget(Summernote::class, [
    // 'useKrajeePresets' => true,
    'pluginOptions' => [
      'dialogsFade' => true,
      'toolbar' => RICH_TOOLBAR,
      'fontSizes' => FONT_SIZES,
    ]
  ])->label(false)
]), ['class' => 'col-md-12']);

echo Html::tag('div', AccordionCard::Widget([
  'title' => ($model->content ? "<i class='fas fa-check-circle text-success mx-2'></i>" : '') . __('Summary'),
  'id' => 'blogSummary',
  'content' => $form->field($model, 'summary')->widget(Summernote::class, [
    // 'useKrajeePresets' => true,
    'pluginOptions' => [
      'dialogsFade' => true,
      'toolbar' => RICH_TOOLBAR,
      'fontSizes' => FONT_SIZES,
    ]
  ])->label(false)
]), ['class' => 'col-md-12']);
