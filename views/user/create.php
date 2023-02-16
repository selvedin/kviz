<?php

/** @var yii\web\View $this */
/** @var app\models\User $model */

$this->title = __('Create') . ' ' . __('User');
?>
<?= $this->render('_form', [
    'model' => $model,
]) ?>