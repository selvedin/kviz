<?php

use app\widgets\CardView;
use app\widgets\UploadFile;


$formID = Yii::$app->params['fileFormName'];

?>
<div id="filesSidebar">
  <?= CardView::begin([
    'buttons' => [UploadFile::widget(['id' => $formID])],
    'title' => __('Images') . "<span> [{{files.length}}]</span>",
    'type' => 'primary'
  ]) ?>

  <div class="progress">
    <div class="progress-bar bg-warning" role="progressbar" style="width:0%;" aria-valuenow="5" aria-valuemin="0" aria-valuemax="100">5%</div>
  </div>
  <div class="row my-4">
    <div v-for="file in files" class="col-md-6 col-12">
      <div class="info-box">
        <div class="info-box-content">
          <div class="row">
            <div class="col my-2">
              <div class="card h-100">
                <img class="card-img-top cursor" :src="'images/places/'+location.id+'/thumbs/'+file" :alt="file" loading="lazy" :data-file='file' onclick="showImage($(this))" />
                <div class="card-body">
                  <h5 class="card-title">{{file}}</h5>
                  <p class="card-text text-end border-top">
                    <a href="#" class="d-none float-right" :data-id="file" onclick="addTag($(this))">
                      <i class="fas fa-tags"></i>
                    </a>
                    <a href="#" class="float-right" :data-id="file" onclick="downloadFile($(this))">
                      <i class="fas fa-cloud-download-alt"></i>
                    </a>
                    <a href="#" class="text-danger float-right" :data-id="file" onclick="deleteFile($(this))">
                      <i class="fas fa-trash"></i>
                    </a>
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?= CardView::end() ?>
</div>