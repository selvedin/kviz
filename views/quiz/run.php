<?php

use app\widgets\CardView;
use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\models\Question $model */

$this->title = __('Running') . '  ' . $model->title;
\yii\web\YiiAsset::register($this);
?>
<div id='quizRunner'>
    <?= CardView::begin([
        'title' => $this->title,
        'type' => 'danger',
        'buttons' => [],
    ]) ?>
    <?= Html::tag('h1', $this->title) ?>
    <?= CardView::end() ?>
</div>