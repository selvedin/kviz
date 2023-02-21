<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Roles $model */

$this->title = __('Update') . ' ' . $model->name . ' ' . __('Role');
?>
<div class="roles-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>