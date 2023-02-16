<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\User $model */

$this->title = $model->name == ' ' ? ucfirst($model->username) : $model->name;
\yii\web\YiiAsset::register($this);
?>
<div class="row">
  <div class="col-12">
    <div class="card card-secondary">

      <div class="card-header text-right">
        <?= Html::a(__('Change password'), ['user/reset-password', 'id' => $model->id], ['class' => 'btn btn-warning']) ?>
      </div>
      <div class="card-body">
        <?= DetailView::widget([
          'model' => $model,
          'attributes' => [
            'first_name',
            'last_name',
            'username',
            'email:email',
            ['attribute' => 'isActive', 'label' => __('Active')],
            ['attribute' => 'roles', 'label' => __('Role')],
          ],
        ]) ?>
      </div>
    </div>
  </div>
</div>