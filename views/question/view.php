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
        'content',
        ['attribute' => 'question_type', 'value' => $model->questionType],
        ['attribute' => 'content_type', 'value' => $model->contentType],
        ['attribute' => 'category_id', 'value' => $model->category],
        ['attribute' => 'status', 'value' => $model->statusLabel],
        ['attribute' => 'grade', 'value' => $model->gradeLabel],
        ['attribute' => 'level', 'value' => $model->levelLabel],
    ],
]);
echo CardView::end();
require_once('view/options.php');
require_once('view/pairs.php');
