<?php

use app\helpers\Buttons;
use app\models\User;
use app\widgets\CardView;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\User $model */

$this->title = $model->username;
\yii\web\YiiAsset::register($this);
?>
<div class="row">
    <div class="col-12">
        <?= CardView::widget([
            'title' => $model->status == 10 ? Html::a(
                __('Change Password'),
                ['reset-password', 'id' => $model->id],
                ['class' => 'btn btn-warning']
            ) : (User::isAdmin() ? Html::a(
                __('Activate User'),
                ['activate', 'id' => $model->id],
                ['class' => 'btn btn-warning']
            ) : $model->name),
            'buttons' => [
                Buttons::List(),
                Buttons::Update('id', $model->id),
                Buttons::Delete('id', $model->id)
            ],
            'content' => DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'first_name',
                    'last_name',
                    'username',
                    'email:email',
                    ['attribute' => 'isActive', 'label' => __('Status')],
                    ['attribute' => 'role.name', 'label' => __('Role')],
                ],
            ])
        ])  ?>
    </div>
</div>