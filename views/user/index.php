<?php

use app\helpers\Buttons;
use app\models\User;
use yii\bootstrap5\Html;
use yii\grid\ActionColumn;
use yii\helpers\Url;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\search\UserSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = __('Users');
$signuUpForm = Html::beginForm(['/user/send-signup'], 'post', ['class' => 'd-flex form-inline w-75 float-right ml-2'])
    . Html::textInput('Signup[email]', '', ['type' => 'email', 'class' => 'form-control w-75', 'placeholder' => __('Email'), 'required' => true])
    . Html::submitButton(
        __('Send signup form'),
        ['class' => 'btn btn-info w-25 text-truncate', 'data' => ['toggle' => 'tooltip']]
    )
    . Html::endForm();
?>
<div class="row">
    <div class="col-12">
        <div class="card card-secondary">
            <div class="card-header">
                <div class="card-tools w-75 float-start">
                    <?= $signuUpForm ?>
                </div>
                <div class="float-end">
                    <?= Buttons::Create() ?>
                    <?= Buttons::ResetList() ?>
                </div>
            </div>
            <div class="card-body">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        'username',
                        'email:email',
                        ['attribute' => 'isActive', 'label' => __('Active'), 'filter' => User::getStatuses()],
                        ['attribute' => 'roles', 'label' => __('Roles'), 'filter' => User::getRolesNames()],
                        [
                            'class' => ActionColumn::class,
                            'urlCreator' => function ($action, User $model, $key, $index, $column) {
                                return Url::toRoute([$action, 'id' => $model->id]);
                            }
                        ],
                    ],
                ]); ?>

            </div>
        </div>
    </div>
</div>