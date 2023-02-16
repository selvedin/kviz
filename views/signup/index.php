<?php

use kartik\grid\ActionColumn;
use kartik\grid\GridView;
use yii\bootstrap4\Html;

/** @var yii\web\View $this */
/** @var app\models\search\SignupSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = __('Signups');
?>
<div class="row">
    <div class="col-12">
        <div class="card card-secondary">
            <div class="card-body">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        'email:email',
                        'expiry_date',
                        ['attribute' => 'is_active', 'value' => function ($model) {
                            return $model->is_active == 10 ? __('Yes') : __('No');
                        }, 'filter' => [9 => __('No'), __('Yes')]],
                        [
                            'template' => '{view}{delete}',
                            'class' => ActionColumn::class
                        ],
                    ],
                    'panel' => [
                        'heading' => Html::tag('h3', $this->title, ['class' => 'panel-title']),
                        'type' => 'secondary',
                    ],
                    'toolbar' => [
                        [
                            'options' => ['class' => 'btn-group-sm']
                        ],
                        '{export}',
                        '{toggleData}'
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>