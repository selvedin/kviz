<?php

use app\helpers\Buttons;
use app\models\Roles;
use app\models\SignupForm;
use app\models\User;
use app\widgets\CardView;
use kartik\select2\Select2;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\models\User $model */
/** @var yii\widgets\ActiveForm $form */
$this->title = __('User');
$passwordButton = null;
if ($model instanceof User)
    $passwordButton = $model->status == 10 ? Html::a(
        __('Change Password'),
        ['reset-password', 'id' => $model->id],
        ['class' => 'btn btn-sm rounded-pill mx-1 text-white btn-warning']
    ) : (User::isAdmin() ? Html::a(
        __('Activate User'),
        ['activate', 'id' => $model->id],
        ['class' => 'btn btn-sm rounded-pill mx-1 text-white btn-warning']
    ) : $model->name);
?>

<?php $form = ActiveForm::begin(); ?>
<?= CardView::begin([
    'title' => $this->title,
    'type' => 'info',
    'buttons' => [
        $passwordButton,
        Buttons::List(),
        Buttons::Save()
    ],
]) ?>

<div class="row mt-4">
    <?= Html::tag('div', $form->field($model, 'first_name')->textInput(), ['class' => 'col-md-6']) ?>
    <?= Html::tag('div', $form->field($model, 'last_name')->textInput(), ['class' => 'col-md-6']) ?>
    <?= Html::tag('div', $form->field($model, 'email')->textInput(), ['class' => 'col-md-6']) ?>
    <?= Html::tag('div', $form->field($model, 'username')->textInput(['autocomplete' => 'new-password']), ['class' => 'col-md-6']) ?>
    <?= Html::tag(
        'div',
        $form->field($model, 'role_id')->widget(
            Select2::class,
            [
                'data' => Roles::getRoles(),
                'options' => ['placeholder' => __('Select a role')],
                'pluginOptions' => [
                    'allowClear' => true
                ]
            ]
        )->label(__('Role')),
        ['class' => 'col-md-6']
    ) ?>
    <?= Html::tag(
        'div',
        $form->field($model, 'status')->widget(
            Select2::class,
            [
                'data' => User::getStatuses(),
                'options' => ['placeholder' => __('Select a status')],
                'pluginOptions' => [
                    'allowClear' => true
                ]
            ]
        ),
        ['class' => 'col-md-6']
    ) ?>
    <?php
    if ($model instanceof SignupForm) {
        echo Html::tag('div', $form->field($model, 'password')->passwordInput(['autocomplete' => 'new-password']), ['class' => 'col-md-6']);
        echo Html::tag('div', $form->field($model, 'password_repeat')->passwordInput(['autocomplete' => 'new-password']), ['class' => 'col-md-6']);
    }
    ?>
</div>
<?= CardView::end() ?>
<?php ActiveForm::end(); ?>