<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\helpers\Url;

$this->title = __('Login');
$this->params['breadcrumbs'][] = $this->title;
$path = Yii::getAlias('@web');
$errors = $model->getErrorSummary(true);
$hasError = count($errors) ? $errors[0] : null;
?>
<div class="authentication-wrapper authentication-basic bg-home">
    <div class="authentication-inner row">
        <div class="d-flex col-12 col-lg-12 align-items-center p-sm-5 p-4">
            <div class="w-px-400 mx-auto">
                <div class="app-brand mb-4">
                    <a href="<?= Url::to(['home']) ?>" class="app-brand-link gap-2">
                        <span class="mx-auto">
                            <i class="fas fa-route fa-3x"></i>
                        </span>
                    </a>
                </div>

                <h3 class="mb-4 fw-bold">
                    <?= Html::a(Yii::$app->name, Url::to(['home'])) ?>
                </h3>
                <p class="mb-4 <?= empty($hasError) ? 'd-none' : ' text-danger' ?>">
                    <strong><?= $hasError ?></strong>
                </p>

                <?php ActiveForm::begin([
                    'id' => 'login-form'
                ]); ?>

                <div class="mb-3">
                    <label for="email" class="form-label"><?= __('Username') ?></label>
                    <input type="text" class="form-control" id="email" name="LoginForm[username]" placeholder="<?= __('Enter your username') ?>" autofocus />
                </div>

                <div class="mb-3 form-password-toggle">
                    <div class="d-flex justify-content-between">
                        <label class="form-label" for="password"><?= __('Password') ?></label>
                        <a href="<?= Url::to(['site/request-password-reset']) ?>">
                            <small><?= __('Forgot Password') ?></small>
                        </a>
                    </div>
                    <div class="input-group input-group-merge">
                        <input type="password" id="password" class="form-control" name="LoginForm[password]" placeholder="******" aria-describedby="password" />
                        <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value id="remember-me" name="LoginForm[rememberMe]" />
                        <label class="form-check-label text-dark" for="remember-me"> <?= __('Remember Me') ?> </label>
                    </div>
                </div>
                <button class="btn btn-primary d-grid w-100"><?= __('Sign in') ?></button>
                <div class="mt-4 border-top d-none">
                    <?= Html::a(__('Need new verification email'), ['site/resend-verification-email']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>