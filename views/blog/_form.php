<?php

use yii\bootstrap5\Html;
use yii\widgets\ActiveForm;


/** @var yii\web\View $this */
/** @var app\models\Blog $model */
/** @var yii\widgets\ActiveForm $form */

$this->title = __('Blog');
if (count($model->errors)) {
    foreach ($model->errors as $error)
        echo Html::tag('div', $error[0], ['class' => 'alert alert-danger']);
}
?>

<div class="row" id='blogApp'>
    <div class="col-12">
        <?php $form = ActiveForm::begin(); ?>
        <div class="card" style="background:none; border:none;box-shadow:none;">
            <?php require_once('parts/header.php') ?>
            <div class="row">
                <?php
                require_once('parts/info.php');
                require_once('parts/rich.php');
                ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>