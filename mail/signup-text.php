<?php

/** @var yii\web\View $this */
/** @var app\models\User $user */

$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['site/signup', 'token' => $model->token]);
?>
Hello <?= $model->email ?>,

Follow the link below to sign up to <?= Yii::$app->name ?> Application:

<?= $verifyLink ?>