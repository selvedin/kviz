<?php

use app\helpers\Buttons;
use app\helpers\Icons;
use app\widgets\RowInput;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = __('School Info');
?>
<div class="row">
  <div class="col-md-12">
    <?php ActiveForm::begin() ?>
    <div class="card card-secondary">
      <div id="sticky-wrapper" class="sticky-wrapper" style="height: 80px;">
        <div class="card-header sticky-element bg-label-info d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row">
          <h1 class="card-title mb-sm-0 me-2"><?= $this->title ?></h1>
          <div class="action-btns">
            <?= Buttons::Save() ?>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <div class="card card-primary card-outline">
              <div class="card-body">
                <?php
                foreach ($fields as $key => $value)
                  echo RowInput::widget(['name' => $key, 'title' => $value, 'value' => set($model, $key)]);
                ?>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card card-primary card-outline">
              <div class="card-body box-profile text-center">
                <div>
                  <img class="profile-user-img img-fluid " :src="basePath+logoImage+'?<?= time() ?>'" alt="Logo">
                </div>
                <ul class="list-group list-group-unbordered mb-3">
                  <div v-if="isUploading" class="progress">
                    <div class="progress-bar bg-warning" role="progressbar" style="width:0%;" aria-valuenow="5" aria-valuemin="0" aria-valuemax="100">5%</div>
                  </div>
                </ul>
                <a href="#" class="btn btn-primary btn-block" @click="selectImage()"><b><?= __('Select image') ?></b></a>
              </div>
              <!-- /.card-body -->
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php ActiveForm::end() ?>
    <form id="imageForm">
      <input type="file" class="d-none" name="logoImage" @change="uploadImage()" id="logoImage" accept=".png">
    </form>
  </div>
</div>