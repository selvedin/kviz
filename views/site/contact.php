<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\ContactForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\captcha\Captcha;
use app\widgets\CardView;


$this->title = 'Kontakt';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="d-flex justify-content-center">
    <div class='col-xl-6 col-md-8 col-sm-12'>
        <h1><?= Html::encode($this->title) ?></h1>
        <?php CardView::begin(__('Contact')) ?>
        <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

        <?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?>

        <?= $form->field($model, 'email') ?>

        <?= $form->field($model, 'subject') ?>

        <?= $form->field($model, 'body')->textarea(['rows' => 6]) ?>

        <div class="row">
            <div class="col-md-12">
                <?= $form->field($model, 'verifyCode')->widget(Captcha::class, [
                    'template' => '<div class="row"><div class="col-md-4 mb-2">{image}</div><div class="col-md-8">{input}</div></div>',
                    'options' => ['placeholder' => __('Verification code')]
                ])->label(false) ?>
            </div>
            <div class="col-md-12 mt-4">
                <?= Html::submitButton(__('Submit'), ['class' => 'btn btn-primary w-100', 'name' => 'contact-button']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
        <?php CardView::end(); ?>
    </div>
</div>