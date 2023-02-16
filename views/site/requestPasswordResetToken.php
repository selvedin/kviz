<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap4\ActiveForm $form */
/** @var \frontend\models\PasswordResetRequestForm $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;

$this->title = __('Request password reset');
$this->params['breadcrumbs'][] = $this->title;
$path = Yii::getAlias('@web');
$errors = $model->getErrorSummary(true);
$hasError = count($errors) ? $errors[0] : null;
?>

<div class="authentication-wrapper authentication-basic bg-home">
    <div class="authentication-inner row">
        <!-- Login -->
        <div class="d-flex col-12 col-lg-12 align-items-center p-sm-5 p-4">
            <div class="w-px-400 mx-auto">
                <!-- Logo -->
                <div class="app-brand mb-4">
                    <a href="<?= Url::to(['home']) ?>" class="app-brand-link gap-2">
                        <span class="mx-auto">
                            <i class="ti ti-books ti-xxl"></i>
                        </span>
                    </a>
                </div>
                <!-- /Logo -->

                <h3 class="mb-4 fw-bold">إدارة <?= Yii::$app->name ?></h3>
                <p class="mb-4 <?= empty($hasError) ? 'd-none' : ' text-danger' ?>">
                    <strong><?= $hasError ?></strong>
                </p>
                <div class="mt-4 border-top">
                    <?= __('Please fill out your email. A link to reset password will be sent there') ?>.
                </div>
                <?php ActiveForm::begin([
                    'id' => 'request-password-reset-form'
                ]); ?>
                <div class="mb-3 pt-4">
                    <label for="email" class="d-none form-label"><?= __('Email') ?></label>
                    <input type="text" class="form-control" id="passwordresetrequestform-email" name="PasswordResetRequestForm[email]" placeholder="<?= __('Enter your email') ?>" autofocus />
                </div>

                <button class="btn btn-primary d-grid w-100"><?= __('Send') ?></button>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
        <!-- /Login -->
    </div>
</div>