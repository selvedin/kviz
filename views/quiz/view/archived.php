<?php

use yii\bootstrap5\Html;

if ($perms->canView('ArchivedQuiz')) :
?>
  <div class='col-12'>
    <div class="card h-100">
      <div class="card-header bg-secondary d-flex justify-content-between">
        <div class="card-title mb-0">
          <h5 class="mb-0 text-white">
            <i class="fas fa-archive"></i>
            <?= __('Archived Quizes') ?>
          </h5>
          <small class="text-muted"></small>
        </div>
      </div>
      <div class="card-body pt-4">
        <div class="row">
          <div class="col-12">
            <div class="table-responsive text-nowrap">
              <table class="table">
                <thead>
                  <tr>
                    <th style="width:5%;">#</th>
                    <th style="width:10%;"><?= __('Num of questions') ?></th>
                    <th><?= __('Competitors') ?></th>
                    <th><?= __('Results') ?></th>
                  </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                  <?php foreach ($model->archived as $k => $archived) {
                    $competitors = $results = "";
                    foreach ($archived->competitors as $c) {
                      $competitors .= Html::a(
                        Html::tag('span', $c->user->name, ['class' => 'ms-auto']),
                        'javascript:void(0)',
                        [
                          'class' => 'rounded-pill btn btn-outline-secondary me-2',
                        ]
                      );
                    }
                    foreach ($archived->userResults as $r) {
                      $totals = unserialize($r->totals);
                      $totals = $totals['totalCorrect'];
                      $results .= Html::tag(
                        'div',
                        Html::tag(
                          'h6',
                          Html::a(
                            $r->user->name,
                            'javascript:void(0)',
                            ['@click' => "getUserSummary($r->id)"]
                          ),
                          ['class' => 'mb-0']
                        )
                          . Html::tag(
                            'div',
                            $totals . "/" . count(unserialize($archived->quiz)),
                            ['class' => 'badge rounded bg-label-success']
                          ),
                        ['class' => 'd-flex gap-2 align-items-center mx-2']
                      );
                    }
                    echo Html::tag(
                      'tr',
                      Html::tag('td', $k + 1 . '.') .
                        Html::tag('td', $archived->quizObject->num_of_questions)
                        . Html::tag('td', $competitors)
                        . Html::tag('td', $results, ['class' => 'd-flex'])
                    );
                  }
                  ?>
                </tbody>
              </table>
            </div>
            <hr>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php
endif;
?>