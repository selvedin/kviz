<?php

use app\helpers\Buttons;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Roles $model */

$this->title = $model->name;
\yii\web\YiiAsset::register($this);
?>
<div class="row">
    <div class="col-md-12">
        <div class="card card-secondary">
            <div class="card-header text-right">
                <?= Buttons::List() ?>
                <?= Buttons::Update('id', $model->id_role) ?>
                <?= Buttons::Delete('id', $model->id_role) ?>
                <?= Buttons::Create() ?>
            </div>
            <div class="card-body">

                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'name',
                        ['attribute' => 'isPrivate', 'label' => __('Private')],
                        'description',
                        'created_on',
                        'createdBy.username',
                        'updated_on',
                        'updatedBy.username',
                    ],
                ]) ?>
            </div>
        </div>
    </div>
</div>