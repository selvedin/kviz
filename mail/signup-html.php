<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\User $user */

$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['site/signup', 'token' => $model->token]);
?>
<div class="verify-email">
    <p>Hello <?= Html::encode($model->email) ?>,</p>

    <p>Follow the link below to signup to <?= Yii::$app->name ?> Application:</p>

    <p><?= Html::a(Html::encode($verifyLink), $verifyLink) ?></p>
</div>