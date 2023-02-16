<?php

use app\helpers\Buttons;
use yii\bootstrap5\Html;

?>
<div id="sticky-wrapper" class="sticky-wrapper" style="height: 80px;">
  <div class="card-header sticky-element bg-label-secondary d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row">
    <h1 class="card-title mb-sm-0 me-2"><?= $this->title ?></h1>
    <div class="action-btns">
      
      <?= \app\models\User::isUser()?null:Html::tag('span', 'عدد الزيارات: '.(int)$model->views, ['class' => 'badge rounded-pill bg-info']) ?>
      <?= Buttons::Save() ?>
      <?= Buttons::List() ?>
      <?= $model->isNewRecord ? '' : Buttons::View('id', $model->id) ?>
    </div>
  </div>
</div>