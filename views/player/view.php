<?php


$this->title = __('Running') . '  ' . $model->title;
\yii\web\YiiAsset::register($this);
?>
<div class='card m-0 h-100'>
    <div class="card-body h-100 d-flex align-items-center">
        <h1 class='text-center w-100'>
            <a href='javascript:void(0)' class='btn rounded-pill btn-outline-primary waves-effect btn-xl w-25' @click='runQuiz()'>
                <?= __('Run quiz') ?>: <strong><?= $model->title ?></strong>
            </a>
        </h1>
    </div>
</div>