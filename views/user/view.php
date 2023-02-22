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
echo CardView::widget([
    'title' => $model->name . ' - ' . __('Profile'),
    'buttons' => [
        $model->status == 10 ? Html::a(
            __('Change Password'),
            ['reset-password', 'id' => $model->id],
            ['class' => 'btn btn-sm rounded-pill mx-1 text-white btn-warning']
        ) : (User::isAdmin() ? Html::a(
            __('Activate User'),
            ['activate', 'id' => $model->id],
            ['class' => 'btn btn-sm rounded-pill mx-1 text-white btn-warning']
        ) : $model->name),
        Buttons::List(),
        Buttons::Update('id', $model->id),
        Buttons::Delete('id', $model->id),

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
]);
