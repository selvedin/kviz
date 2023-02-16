<?php

use yii\helpers\Url;
use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

$this->title = __('Signup');
?>
<div class="login-box">
  <!-- /.login-logo -->
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="<?= Url::to(['site/index']) ?>" class="h1"><b><?= Yii::$app->name ?></b></a>
    </div>
    <div class="card-body">
      <p class="login-box-msg"><?= __('Sign up for Application') ?></p>
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
    </div>
    <div class="card-footer">
      <div class="row">
        <div class="col-12">
          <div style="color:#999;">
            <?= __('Already have an account') ?>? <?= Html::a(__('Login'), ['site/login']) ?>.
          </div>
        </div>
      </div>
    </div>

  </div>
  <!-- /.card-body -->
</div>
<!-- /.card -->
</div>
<!-- /.login-box --
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