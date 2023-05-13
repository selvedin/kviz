<?php

use app\helpers\Buttons;
use app\models\Perms;
use app\widgets\CardView;
use kartik\editors\Summernote;
use yii\bootstrap5\Html;

$options = [
  'pluginOptions' => [
    'dialogsFade' => true,
    'toolbar' => RICH_TOOLBAR,
    'fontSizes' => FONT_SIZES,
    'lang' => 'hr-HR',
  ]
];

/** @var yii\web\View $this */
/** @var app\models\Question $model */

$this->title = __('Ocr');
\yii\web\YiiAsset::register($this);
$perms = new Perms();
?>
<div id="quizApp">
  <?= CardView::begin([
    'title' => $this->title,
    'type' => 'info',
    'buttons' => [
      Buttons::customButton(__('Existing'), ['gpt/ocr-list'], [], 'dark rounded-pill btn-sm')
    ],
  ]) ?>
  <br />
  <div class="row">
    <div class="col-xl-6 col-md-6 mb-4">
      <?= CardView::begin([
        'title' => __('Form'),
        'type' => 'secondary',
        'buttons' => []
      ]) ?>
      <?php require_once('ocr_form.php') ?>
      <?= CardView::end() ?>
    </div>
    <div class="col-xl-6 col-md-6 mb-4">

      <?= CardView::begin([
        'title' => __('Response'),
        'type' => 'secondary',
        'buttons' => []
      ]) ?>
      <div class="row">
        <div class="col-12 mt-4">
          <?= Html::textarea('ocr_text', $response, ['class' => 'form-control', 'rows' => 22]) ?>
        </div>
      </div>
      <?= CardView::end() ?>
    </div>
  </div>
  <?= CardView::end(); ?>
</div>