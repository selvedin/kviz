<?php

use app\helpers\Buttons;
use app\models\Roles;
use app\models\User;
use app\widgets\CardView;
use yii\bootstrap5\Html;
use yii\grid\ActionColumn;
use yii\helpers\Url;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\search\UserSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = __('Users');
$signuUpForm = Html::beginForm(['/user/send-signup'], 'post', ['class' => 'd-flex form-inline w-50 float-right ml-2'])
    . Html::textInput('Signup[email]', '', ['type' => 'email', 'class' => 'form-control w-75', 'placeholder' => __('Email'), 'required' => true])
    . Html::submitButton(
        __('Send signup form'),
        ['class' => 'btn btn-primary w-25 text-truncate', 'data' => ['toggle' => 'tooltip']]
    )
    . Html::endForm();

echo  CardView::begin([
    'title' => $this->title,
    'type' => 'info',
    'buttons' => [
        Buttons::Create(),
        Buttons::ResetList(),
    ],
]);
echo Html::tag(
    'div',
    Html::tag('div', $signuUpForm, ['class' => 'col-12 d-flex justify-content-end']),
    ['class' => 'row my-2']
);
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'username',
        'email:email',
        ['attribute' => 'isActive', 'label' => __('Active'), 'filter' => User::getStatuses()],
        ['attribute' => 'role_id', 'label' => __('Role'), 'value' => function ($model) {
            return $model->role?->name;
        }, 'filter' => Roles::getNames()],
        [
            'class' => ActionColumn::class,
            'urlCreator' => function ($action, User $model, $key, $index, $column) {
                return Url::toRoute([$action, 'id' => $model->id]);
            }
        ],
    ],
]);
echo CardView::end();
