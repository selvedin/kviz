<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Pairs $model */

$this->title = 'Create Pairs';
$this->params['breadcrumbs'][] = ['label' => 'Pairs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pairs-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
