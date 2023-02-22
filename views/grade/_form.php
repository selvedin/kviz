<?php

use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Grade $model */
/** @var yii\widgets\ActiveForm $form */
$this->title = __('Grade');
?>

<div class="row" id='quizApp'>
    <div class="col-12">
        <?php $form = ActiveForm::begin(); ?>
        <div class="card" style="background:none; border:none;box-shadow:none;">
            <?php require_once(__DIR__ . '/../base/header.php') ?>
            <div class="row">
                <?php
                require_once('parts/info.php');
                ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>