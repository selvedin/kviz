<?php

use yii\helpers\Url;
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = __('Signup');
?>
<div class="authentication-wrapper authentication-basic container-p-y">
  <div class="authentication-inner py-4">
    <!-- Login -->
    <div class="card">
      <div class="card-body">
        <!-- Logo -->
        <div class="app-brand justify-content-center mb-4 mt-2">
          <a href="<?= Url::to(['home']) ?>" class="app-brand-link gap-2">
            <span class="app-brand-logo demo">
              <svg width="32" height="22" viewBox="0 0 32 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M0.00172773 0V6.85398C0.00172773 6.85398 -0.133178 9.01207 1.98092 10.8388L13.6912 21.9964L19.7809 21.9181L18.8042 9.88248L16.4951 7.17289L9.23799 0H0.00172773Z" fill="#7367F0"></path>
                <path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd" d="M7.69824 16.4364L12.5199 3.23696L16.5541 7.25596L7.69824 16.4364Z" fill="#161616"></path>
                <path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd" d="M8.07751 15.9175L13.9419 4.63989L16.5849 7.28475L8.07751 15.9175Z" fill="#161616"></path>
                <path fill-rule="evenodd" clip-rule="evenodd" d="M7.77295 16.3566L23.6563 0H32V6.88383C32 6.88383 31.8262 9.17836 30.6591 10.4057L19.7824 22H13.6938L7.77295 16.3566Z" fill="#7367F0"></path>
              </svg>
            </span>
            <span class="app-brand-text demo text-body fw-bold ms-1">Kviz</span>
          </a>
        </div>
        <!-- /Logo -->
        <h4 class="mb-1 pt-2">Dobrodošli u Kviz 👋</h4>
        <p class="mb-4">Registrujte se i započnite avanturu!</p>
        <?php $form = ActiveForm::begin(['id' => 'sognup-form']); ?>
        <?= $form->field(
          $model,
          'first_name',
          ['template' => "<div class='input-group mb-3'>{input}" . getTemplate('signature') . "</div>"]
        )
          ->textInput(['autofocus' => true, 'placeholder' => __('First name')]) ?>

        <?= $form->field(
          $model,
          'last_name',
          ['template' => "<div class='input-group mb-3'>{input}" . getTemplate('signature') . "</div>"]
        )
          ->textInput(['autofocus' => true, 'placeholder' => __('Last name')]) ?>


        <?= $form->field(
          $model,
          'username',
          ['template' => "<div class='input-group mb-3'>{input}" . getTemplate('user') . "</div>"]
        )
          ->textInput(['autofocus' => true, 'placeholder' => __('Username')]) ?>

        <?= $form->field(
          $model,
          'email',
          ['template' => "<div class='input-group mb-3'>{input}" . getTemplate('envelope') . "</div>"]
        )
          ->textInput(['type' => 'email', 'placeholder' => __('Email'), 'readonly' => true]) ?>

        <?= $form->field(
          $model,
          'password',
          ['template' => "<div class='input-group mb-3'>{input}{error}" . getTemplate('key') . "</div>"]
        )
          ->passwordInput(['autofocus' => true, 'placeholder' => __('Password')]) ?>

        <?= $form->field(
          $model,
          'password_repeat',
          ['template' => "<div class='input-group mb-3'>{input}{error}" . getTemplate('key') . "</div>"]
        )
          ->passwordInput(['autofocus' => true, 'placeholder' => __('Confirm password')]) ?>
        <!-- /.col -->
        <div class="col-12">
          <?= Html::submitButton('Signup', ['class' => 'btn btn-primary btn-block', 'name' => 'login-button']) ?>
        </div>
        <!-- /.col -->
        <?php ActiveForm::end(); ?>
        <p class="text-center mt-4">
          <span>Imate račun?</span>
          <a href="<?= Url::to(['site/login']) ?>">
            <span>Prijavite se</span>
          </a>
        </p>

        <div class="d-none divider my-4">
          <div class="divider-text">ili</div>
        </div>

        <div class="d-none justify-content-center">
          <a href="javascript:;" class="btn btn-icon btn-label-facebook me-3 waves-effect">
            <i class="tf-icons fa-brands fa-facebook-f fs-5"></i>
          </a>

          <a href="javascript:;" class="btn btn-icon btn-label-google-plus me-3 waves-effect">
            <i class="tf-icons fa-brands fa-google fs-5"></i>
          </a>

          <a href="javascript:;" class="btn btn-icon btn-label-twitter waves-effect">
            <i class="tf-icons fa-brands fa-twitter fs-5"></i>
          </a>
        </div>
      </div>
    </div>
    <!-- /Register -->
  </div>
</div>
<?php
function getTemplate($icon = 'envelope')
{
  return "<div class='input-group-append'>
  <div class='input-group-text'>
    <span class='fas fa-$icon'></span>
  </div>
</div>";
}
?>