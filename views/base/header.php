<?php

use app\helpers\Buttons;
use app\helpers\Helper;

$buttons = [Buttons::Save(), Buttons::List(), $model->isNewRecord ? null : Buttons::View('id', $model->id)];
?>
<div id="sticky-wrapper" class="sticky-wrapper" style="height: 80px;">
  <div class="card-header sticky-element bg-label-info d-flex justify-content-between">
    <h1 class="me-auto"><?= $this->title ?></h1>
    <div class="ms-auto">
      <ul class='card-inlinelist list-inline mb-0'>
        <li class='list-inline-item'>
          <?php foreach ($buttons as $button) echo $button; ?>
        </li>
      </ul>
      <?= Helper::dropDown($buttons) ?>
    </div>
  </div>
</div>