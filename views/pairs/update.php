<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Pairs $model */

$this->title = 'Update Pairs: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Pairs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pairs-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
