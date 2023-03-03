<?php

use app\widgets\CardView;
use app\helpers\Icons;
use yii\helpers\Url;

$this->title = __('My Quizes');
$path = Yii::getAlias('@web') . '/img/illustrations/';
?>
<?= CardView::begin([
  'title' => $this->title,
  'type' => 'info',
  'buttons' => [],
]) ?>
<div class="row mt-4">
  <?php

  foreach ($quizes as $k => $quiz) :
    $myTotals = unserialize($quiz->myResults->totals);
    $class = "btn-outline-white";
    $icon = Icons::faIcon('chart-line ms-2');
    $text = __('See results');
  ?>
    <div class="col-xl-3 mb-4 col-lg-4 col-12">
      <div class="card bg-secondary text-white">
        <div class="d-flex align-items-end row">
          <div class="col-7">
            <div class="card-body text-nowrap">
              <h5 class="card-title mb-0"><?= $quiz->quizObject->title ?> 🎉</h5>
              <h4 class="text-white mb-1"><?= $myTotals['totalCorrect'] ?> / <?= $quiz->quizObject->num_of_questions ?></h4>
              <a href="<?= Url::to(['reports/view', 'id' => $quiz->myResults->id]) ?>" class="btn <?= $class ?> rounded-pill waves-effect waves-light">
                <?= $text . ' ' . $icon ?>
              </a>
            </div>
          </div>
          <div class="col-5 text-center text-sm-left">
            <div class="card-body pb-0 px-0 px-md-4">
              <img src="<?= $path . IMAGES[rand(0, count(IMAGES) - 1)] ?>" height="140" alt="view sales">
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php
  endforeach;
  ?>
</div>
<?= CardView::end(); ?>