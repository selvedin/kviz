<?php

use app\helpers\Buttons;
use app\helpers\Icons;
use app\models\Question;
use app\widgets\CardView;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Question $model */

$this->title = __('Question');
\yii\web\YiiAsset::register($this);
echo  CardView::begin([
    'title' => $this->title,
    'type' => 'info',
    'buttons' => [
        Buttons::List(),
        __isUser(Buttons::Update('id', $model->id)),
    ],
]);
echo DetailView::widget([
    'model' => $model,
    'attributes' => [
        'title', 'num_of_questions', 'duration',
        ['attribute' => 'status', 'value' => $model->statusLabel],
        ['attribute' => 'grade', 'value' => $model->gradeLabel],
        ['attribute' => 'level', 'value' => $model->levelLabel],
        ['attribute' => 'moderator_id', 'value' => $model->moderator->name],
    ],
]);
echo CardView::end();
require_once('view/config.php');
