<?php

use app\helpers\Buttons;
use app\widgets\CardView;

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
?>
<br />
<div class="row">
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between">
                <div class="card-title mb-0">
                    <h5 class="mb-0"><?= __('Title') ?></h5>
                    <small class="text-muted"></small>
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
    <?php
    require_once('view/options.php');
    require_once('view/pairs.php');
    ?>
</div>
<?= CardView::end() ?>