<?php

use yii\bootstrap5\ActiveForm;

$this->title = __('Quiz');

?>

<div class="row" id='quizApp'>
  <div class="col-12">
    <?php $form = ActiveForm::begin(); ?>
    <div class="card" style="background:none; border:none;box-shadow:none;">
      <?php require_once(__DIR__ . '/../base/header.php') ?>
      <div class="row">
        <?php
        require_once('parts/info.php');
        require_once('parts/config.php');
        ?>
      </div>
    </div>
    <?php ActiveForm::end(); ?>
  </div>
</div>