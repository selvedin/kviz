<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap4\ActiveForm $form */
/** @var \frontend\models\ResetPasswordForm $model */

use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;

$path = Yii::getAlias('@web');
$errors = $model->getErrorSummary(true);
$hasError = count($errors) ? $errors[0] : null;

$this->title = __('Reset password');
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

                <h3 class="mb-4 fw-bold"><?= Yii::$app->name ?></h3>
                <p class="mb-4 <?= empty($hasError) ? 'd-none' : ' text-danger' ?>">
                    <strong><?= $hasError ?></strong>
                </p>
                <?php ActiveForm::begin([
                    'id' => 'reset-password-form'
                ]); ?>
                <div class="mb-3 pt-4">
                    <label for="email" class="form-label"><?= __('Please choose your new password') ?></label>
                    <input type="password" class="form-control" id="resetpasswordform-password" name="ResetPasswordForm[password]" autofocus placeholder="********" aria-describedby="password" />
                </div>

                <button class="btn btn-primary d-grid w-100"><?= __('Save') ?></button>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
        <!-- /Login -->
    </div>
</div>