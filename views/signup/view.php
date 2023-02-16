<?php

use app\helpers\Buttons;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Signup $model */

$this->title = $model->email;
\yii\web\YiiAsset::register($this);
?>
<div class="row">
    <div class="col-12">
        <div class="card card-secondary">
            <div class="card-header">
                <div class="card-tools">
                    <?= Buttons::List() ?>
                    <?= Buttons::Delete('id', $model->id) ?>
                </div>
            </div>
            <div class="card-body">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'email:email',
                        'expiry_date',
                        ['attribute' => 'is_active', 'value' => $model->is_active == 10 ? __('Yes') : __('No')],
                    ],
                ]) ?>
            </div>
        </div>
    </div>
</div>