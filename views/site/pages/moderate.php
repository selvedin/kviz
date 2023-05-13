<div class="row">
  <?php

  use app\helpers\Icons;
  use yii\helpers\Url;


  foreach ($moderate as $k => $quiz) :
    $class = "btn-outline-warning";
    $icon = Icons::faIcon('remote ms-2');
    $text = __('Moderate now');
  ?>
    <div class="mb-4 col-xxl-3 col-lg-4 col-md-6 col-sm-12">
      <div class="card bg-dark">
        <div class="d-flex align-items-end row">
          <div class="col-7">
            <div class="card-body text-nowrap">
              <h5 class="card-title text-info mb-0"><?= $quiz->quizObject->title ?> 🎉</h5>
              <p class="text-secondary mb-2"><?= __('Num Of Questions') ?></p>
              <h4 class="text-warning mb-1"><?= $quiz->quizObject->num_of_questions ?></h4>
              <a href="<?= Url::to(['player/view', 'id' => $quiz->id, 'moderate' => 1]) ?>" class="btn <?= $class ?> rounded-pill waves-effect waves-light">
                <?= "$text $icon" ?>
              </a>
            </div>
          </div>
          <div class="col-5 text-center text-sm-left">
            <div class="card-body pb-0 px-0 px-md-4">
              <img src="<?= $path .  IMAGES[rand(0, count(IMAGES) - 1)] ?>" class='quiz-image' alt="view sales">
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php
  endforeach;
  ?>
</div>