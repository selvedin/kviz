<div class="row">
  <?php

  use app\helpers\Icons;
  use app\models\Quiz;
  use yii\helpers\Url;

  $images = [
    'card-advance-sale.png',
    'boy-verify-email-dark.png',
    'boy-with-laptop-dark.png',
    'boy-with-rocket-light.png',
    'girl-unlock-password-light.png',
    'girl-doing-yoga-light.png',
    'girl-verify-password-dark.png',
    'girl-with-laptop.png',
    'girl-with-laptop-light.png',
    'page-misc-under-maintenance.png',
    'auth-forgot-password-illustration-light.png'
  ];
  foreach ($quizes as $k => $quiz) :
    $disabled = false;
    $isPlayed = $quiz->isPlayed();
    $class = "btn-outline-primary";
    $icon = Icons::faIcon('hand ms-2');
    $text = __('Play now');
    if ($quiz->quizObject->quiz_type == Quiz::TYPE_REMOTE) {
      $icon = Icons::faIcon('wifi ms-2');
      $text =  __('Waiting to start');
      $class =  "btn-outline-warning";
      $disabled = true;
      if ($quiz->active == Quiz::STATUS_RUNNING) {
        $disabled = false;
        $text =  __('Join now');
        $class = "btn-outline-success animate__animated animate__pulse animate__infinite";
      }
    }
  ?>
    <div class="col-xl-3 mb-4 col-lg-4 col-12">
      <div class="card">
        <div class="d-flex align-items-end row">
          <div class="col-7">
            <div class="card-body text-nowrap">
              <h5 class="card-title mb-0"><?= $quiz->quizObject->title ?> 🎉</h5>
              <p class="mb-2"><?= __('Num Of Questions') ?></p>
              <h4 class="text-primary mb-1"><?= $quiz->quizObject->num_of_questions ?></h4>
              <a href="<?= Url::to($disabled ? 'javascript:void(0)' : ['player/' . ($isPlayed ? 'results' : 'view'), 'id' => $quiz->id]) ?>" class="btn <?= $class ?> rounded-pill waves-effect waves-light">
                <?= $isPlayed ? __('See results') : ("$text $icon") ?>
              </a>
            </div>
          </div>
          <div class="col-5 text-center text-sm-left">
            <div class="card-body pb-0 px-0 px-md-4">
              <img src="img/illustrations/<?= $images[rand(0, count($images) - 1)] ?>" height="140" alt="view sales">
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php
  endforeach;
  ?>
</div>