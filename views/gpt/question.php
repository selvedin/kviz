<?php

use app\helpers\Icons;
use app\models\Perms;
use app\widgets\CardView;
use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\models\Question $model */

$this->title = __('Generating Questions');
\yii\web\YiiAsset::register($this);
$perms = new Perms();
$range = range(1, 10);
?>
<div id="questionGeneratorApp">
  <?= CardView::begin($this->title, 'info', []) ?>
  <br />
  <div class="row">
    <div class="col-xl-6 col-md-6 mb-4">
      <div class="card h-100">
        <div class="card-header d-flex justify-content-between">
          <div class="card-title mb-0">
            <h5 class="mb-0"><?= $this->title ?></h5>
          </div>
        </div>
        <div class="card-body">
          <?php
          if ($total_calls >= Yii::$app->params['max_api_calls'])
            echo Html::tag('div', __('You have reached the maximum number of API calls for today. Please try again tomorrow.'), ['class' => 'alert alert-danger']);
          else require_once('form.php') ?>
        </div>
      </div>
    </div>
    <?php

    if (!empty($model->results)) require_once('results.php');
    else require_once('files.php'); ?>

  </div>
  <?= CardView::end(); ?>
</div>