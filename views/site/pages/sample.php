<?php

use app\models\Question;
use app\models\Quiz;
use yii\bootstrap5\Html;

?>
<div class="row">
  <div class="col-lg-6 mb-4">
    <div class="swiper-container swiper-container-horizontal swiper swiper-card-advance-bg swiper-initialized swiper-horizontal swiper-pointer-events swiper-rtl swiper-backface-hidden" id="swiper-with-pagination-cards">
      <div class="swiper-wrapper" id="swiper-wrapper-e061e666ca4b409c" aria-live="off" style="transform: translate3d(2628px, 0px, 0px); transition-duration: 0ms;">
        <?php
        foreach ($quizes as $k => $quiz) :
        ?>
          <div class="swiper-slide swiper-slide-active" data-swiper-slide-index="<?= $k ?>" role="group" aria-label="<?= $k ?> / <?= count($quizes) ?>" style="width: 876px;">
            <div class="row">
              <div class="col-12">
                <h5 class="text-white mb-0 mt-2"><?= $quiz->quizObject->title ?></h5>
                <small><?= __('Num of questions') ?>: <?= $quiz->quizObject->num_of_questions ?></small>
              </div>
              <div class="col-lg-7 col-md-9 col-12 order-2 order-md-1">
                <h6 class="text-white mt-0 mt-md-3 mb-3">
                  <?= Html::a('Pokusaj odmah', ['player/view', 'id' => $quiz->quizObject->id, 'test' => 1], ['class' => 'text-white']) ?>
                </h6>
                <div class="row">
                  <div class="col-6">
                    <ul class="list-unstyled mb-0">
                      <li class="d-flex mb-4 align-items-center">
                        <p class="mb-0 fw-semibold me-2 website-analytics-text-bg">__</p>
                        <p class="mb-0">__</p>
                      </li>
                      <li class="d-flex align-items-center mb-2">
                        <p class="mb-0 fw-semibold me-2 website-analytics-text-bg">__</p>
                        <p class="mb-0">__</p>
                      </li>
                    </ul>
                  </div>
                  <div class="col-6">
                    <ul class="list-unstyled mb-0">
                      <li class="d-flex mb-4 align-items-center">
                        <p class="mb-0 fw-semibold me-2 website-analytics-text-bg">__</p>
                        <p class="mb-0">__</p>
                      </li>
                      <li class="d-flex align-items-center mb-2">
                        <p class="mb-0 fw-semibold me-2 website-analytics-text-bg">__</p>
                        <p class="mb-0">__</p>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="col-lg-5 col-md-3 col-12 order-1 order-md-2 my-4 my-md-0 text-center">
                <img src="img/illustrations/boy-verify-email-dark.png" alt="Website Analytics" width="170" class="card-website-analytics-img">
              </div>
            </div>
          </div>
        <?php
        endforeach;
        ?>
      </div>
      <div class="swiper-pagination swiper-pagination-clickable swiper-pagination-bullets swiper-pagination-horizontal"><span class="swiper-pagination-bullet" tabindex="0" role="button" aria-label="Go to slide 1"></span><span class="swiper-pagination-bullet" tabindex="0" role="button" aria-label="Go to slide 2"></span><span class="swiper-pagination-bullet swiper-pagination-bullet-active" tabindex="0" role="button" aria-label="Go to slide 3" aria-current="true"></span></div>
      <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
    </div>
  </div>
</div>