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
if ($model->options) {
    echo  CardView::begin([
        'title' => __('Options'),
        'type' => 'warning',
        'buttons' => [],
    ]);
?>
    <div class="table-responsive text-nowrap">
        <table class="table">
            <tbody class="table-border-bottom-0">
                <?php
                foreach ($model->options as $opt)
                    echo Html::tag(
                        'tr',
                        Html::tag('td', $opt->content) .
                            Html::tag(
                                'td',
                                ($opt->is_true
                                    ? Icons::faIcon('check text-success')
                                    : Icons::faIcon('times text-danger')),
                                ['class' => 'text-end']
                            )
                    );
                ?>
            </tbody>
        </table>
    </div>
<?php
    echo CardView::end();
}
