<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Roles $model */

$this->title = __('Create') . ' ' . __('Role');
?>
<div class="roles-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>