<?php

use app\helpers\Buttons;
use app\helpers\Icons;
use app\models\Perms;
use app\widgets\CardView;
use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\models\Question $model */

$this->title = __('Quiz');
\yii\web\YiiAsset::register($this);
$perms = new Perms();
?>
<div id="quizApp">
    <?= CardView::begin([
        'title' => $this->title,
        'type' => 'info',
        'buttons' => [
            Buttons::List(),
            __isUser(Buttons::Update('id', $model->id)),
            Buttons::Create(),
            __isUser(Buttons::customButton(
                Icons::faIcon('blender me-2') .  __('Prepare'),
                ['quiz/prepare', 'id' => $model->id],
                [],
                'btn btn-sm rounded-pill mx-1 text-white btn-warning'
            )),
        ],
    ]) ?>
    <br />
    <div class="row">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between">
                    <div class="card-title mb-0">
                        <h5 class="mb-0"><?= $model->title ?></h5>
                        <small class="text-muted"><?= __('Title') ?></small>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="p-0 m-0">
                        <?php
                        foreach ($model->fields as $field) :
                        ?>
                            <li class="mb-4 pb-1 d-flex justify-content-between align-items-center">
                                <div class="d-flex justify-content-between w-100 flex-wrap">
                                    <h6 class="mb-0 ms-3">
                                        <?= $field['label'] ?>
                                    </h6>
                                    <div class="d-flex">
                                        <p class="mb-0 fw-semibold">
                                            <?= $field['value'] ?>
                                        </p>
                                    </div>
                                </div>
                            </li>
                        <?php
                        endforeach;
                        ?>
                    </ul>
                </div>
            </div>
        </div>

        <?php require_once('view/config.php'); ?>
        <?php require_once('view/pending.php'); ?>
        <?php require_once('view/active.php'); ?>
        <?php require_once('view/archived.php'); ?>

    </div>
    <?= CardView::end(); ?>
    <?php require_once('view/competitorModal.php'); ?>
    <?php require_once('view/summaryModal.php'); ?>
</div>
<?php
function __badge($text = null)
{
    $text = $text ? $text : Html::tag('i', '', ['class' => 'fas fa-times']);
    return Html::tag(
        'span',
        $text,
        [
            'class' => 'badge badge-center rounded-pill bg-danger ms-4',
            'style' => 'margin-right:-15px;'
        ]
    );
}
?>