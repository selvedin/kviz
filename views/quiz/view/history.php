<?php

use app\models\Question;
use yii\bootstrap5\Html;

if ($perms->canView('QuizHistory')) :
?>
  <div class='col-12'>
    <div class="card h-100">
      <div class="card-header bg-warning d-flex justify-content-between">
        <div class="card-title mb-0">
          <h5 class="mb-0 text-white">
            <i class="fas fa-clock"></i>
            <?= __('History') ?>
          </h5>
          <small class="text-muted"></small>
        </div>
      </div>
      <div class="card-body pt-4">
        <?php foreach ($model->history as $hist) {
          $quiz = unserialize($hist->quiz);
          $results = unserialize($hist->results);
          $results = $results['results'];
        ?>
          <div class="row">
            <div class="col-12">
              <div class="title d-flex">
                <div class='mx-5'>
                  <?= __('Date') . ": " . Html::tag('strong', date("d.m.Y", $hist->created_at)) ?>
                </div class='mx-5'>
                <div>
                  <?= __('Started') . ": " . Html::tag('strong', date("H:m:s", $hist->created_at)) ?>
                </div>
                <div class='mx-5'>
                  <?= __('Finished') . ": " . Html::tag('strong', date("H:m:s", $hist->updated_at)) ?>
                </div>
              </div>
              <hr>
              <div class="table-responsive text-nowrap">
                <table class="table">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th><?= __('Question') ?></th>
                      <th><?= __('Options') ?></th>
                      <th><?= __('Right') ?></th>
                      <th><?= __('Answer') ?></th>
                      <th><?= __('Correct') ?></th>
                    </tr>
                  </thead>
                  <tbody class="table-border-bottom-0">
                    <?php
                    foreach ($quiz as $k => $q) { // id, content, question_type, options (optional), pairs (optional)
                      $result = getResult($q['id'], $results);
                      $answer = isset($result['content']) ? $result['content'] : null;
                      $question  = Question::findOne((int)$q['id']);
                      $rightOptions = $question->CorrectOptionsAsString();
                      echo Html::tag(
                        'tr',
                        Html::tag('td', $k + 1 . '.') .
                          Html::tag('td', $q['content'])
                          . Html::tag('td', $question->OptionsAsString())
                          . Html::tag('td', $rightOptions)
                          . Html::tag('td', $answer)
                          . Html::tag('td', trim($answer) == trim($rightOptions) ?
                            Html::tag('span', __('Correct'), ['class' => 'text-success']) :
                            Html::tag('span', __('Incorrect'), ['class' => 'text-danger']))
                      );
                    }
                    ?>
                  </tbody>
                </table>
              </div>
              <hr>
            </div>
          </div>
        <?php } ?>
      </div>
    </div>
  </div>
<?php
endif;
function getResult($id, $results)
{
  foreach ($results as $result)
    if ($result['question'] == $id) return $result;
  return null;
}
?>