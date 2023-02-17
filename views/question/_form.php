<?php

use yii\bootstrap5\ActiveForm;

$this->title = __('Question');

?>

<div class="row" id='questionApp'>
  <div class="col-12">
    <?php $form = ActiveForm::begin(); ?>
    <div class="card" style="background:none; border:none;box-shadow:none;">
      <?php require_once('parts/header.php') ?>
      <div class="row">
        <?php
        require_once('parts/info.php');
        require_once('parts/options.php');
        ?>
      </div>
    </div>
    <?php ActiveForm::end(); ?>
  </div>
</div>