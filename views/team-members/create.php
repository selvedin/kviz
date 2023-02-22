<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\TeamMembers $model */

$this->title = 'Create Team Members';
$this->params['breadcrumbs'][] = ['label' => 'Team Members', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="team-members-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
