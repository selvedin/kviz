<?php

use app\helpers\Icons;
use app\models\Question;
use app\widgets\CardView;
use yii\bootstrap5\Html;

$this->title = __('Details');
$quiz = unserialize($model->quiz);
$options = $corrects = $summaries = $totals = [];
$totalQuestion = count($quiz);

foreach ($quiz as $q) {
  $question = Question::findOne((int)$q['id']);
  $options[] = $question->OptionsAsString();
  $corrects[] = $question->CorrectOptionsAsString();
}
?>
<?= CardView::begin([
  'title' => __('Quiz') . ': ' . $model->quizObject->title . ', '
    . Html::tag('small', date('d.m.Y', $model->updated_at), ['class' => 'text-dark']),
  'buttons' => [],
  'type' => 'info'
]);
?>
<div id="reportsApp">
  <?php foreach ($model->userResults as $result) :
    $totalCorrect = unserialize($result->totals);
    $totalQuestions = count(unserialize($model->quiz));
    $summary = unserialize($result->summary);
    $totalCorrect = $totalCorrect['totalCorrect'];
    $percentage = round($totalCorrect /  $totalQuestions * 100);
  ?>
    <div class="row mt-4">
      <div class="col-12 bg-dark text-white w-100 p-2 collapsed text-start" data-bs-toggle="collapse" href="#collapse<?= $result->id ?>" role="button" aria-expanded="false" aria-controls="collapse<?= $result->id ?>">
        <div class="row">
          <div class="col-md-4"><?= $result->user->name ?> <span class='badge bg-warning'>[<?= $totalCorrect . '/' .  $totalQuestions ?>]</span></div>
          <div class="col-md-8">
            <div class="progress mt-1">
              <div class="progress-bar bg-warning" role="progressbar" style="width: <?= $percentage ?>%" aria-valuenow="<?= $percentage ?>" aria-valuemin="0" aria-valuemax="100">
                <?= $percentage ?>%
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-12 collapse" id="collapse<?= $result->id ?>">
        <div class="d-grid d-sm-flex p-3 border">
          <table class="table">
            <thead>
              <tr>
                <th>#</th>
                <th><?= __('Question') ?></th>
                <th><?= __('Options') ?></th>
                <th><?= __('Right') ?></th>
                <th><?= __('Answer') ?></th>
                <th><?= __('Is correct') ?></th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach ($summary as $k => $item) {
                echo Html::tag(
                  'tr',
                  Html::tag('td', ++$k . '.') .
                    Html::tag('td', $item['title']) .
                    Html::tag('td', $item['options']) .
                    Html::tag('td', $item['correct']) .
                    Html::tag('td', $item['answer']) .
                    Html::tag('td', $item['isCorrect'] ? Icons::Correct() : Icons::Incorrect())
                );
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    <?php
  endforeach;
    ?>
    </div>

</div>

<?= CardView::end() ?>