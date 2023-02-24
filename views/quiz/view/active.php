<?php

use app\helpers\Buttons;
use yii\bootstrap5\Html;

if ($perms->canView('ActiveQuiz')) :
?>
  <div class='col-12'>
    <div class="card h-100">
      <div class="card-header bg-primary d-flex justify-content-between">
        <div class="card-title mb-0">
          <h5 class="mb-0 text-white">
            <i class="fas fa-rocket"></i>
            <?= __('Active Quizes') ?>
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
                    <th style="width:20%;"></th>
                  </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                  <?php foreach ($model->active as $k => $active) {
                    $competitors = $results = "";
                    foreach ($active->competitors as $c) {
                      $competitors .= Html::a(
                        Html::tag('span', $c->user->name, ['class' => 'ms-auto']),
                        'javascript:void(0)',
                        [
                          'class' => 'rounded-pill btn btn-outline-secondary me-2',
                        ]
                      );
                    }
                    foreach ($active->userResults as $r) {
                      $totals = unserialize($r->totals);
                      $totals = $totals['totalCorrect'];
                      $results .= Html::tag(
                        'div',
                        Html::tag(
                          'h6',
                          Html::a($r->user->name, 'javascript:void(0)', ['@click' => "getUserSummary($r->id)"]),
                          ['class' => 'mb-0']
                        ) .
                          Html::tag(
                            'div',
                            $totals . "/" . count(unserialize($active->quiz)),
                            ['class' => 'badge rounded bg-label-success']
                          ),
                        ['class' => 'd-flex gap-2 align-items-center mx-2']
                      );
                    }
                    echo Html::tag(
                      'tr',
                      Html::tag('td', $k + 1 . '.') .
                        Html::tag('td', $active->quizObject->num_of_questions)
                        . Html::tag('td', $competitors)
                        . Html::tag('td', $results, ['class' => 'd-flex'])
                        . Html::tag(
                          'td',
                          __isUser(Buttons::Pdf($active->id, 'quiz-temp')) .
                            (count($active->userResults) ? Html::a(
                              __('Archive'),
                              ['quiz/activate', 'id' => $active->id, 'active' => 2],
                              ['class' => 'btn btn-sm btn-outline-secondary rounded-pill ms-2']
                            ) : Html::a(
                              __('Deactive'),
                              ['quiz/activate', 'id' => $active->id, 'active' => 0],
                              ['class' => 'btn btn-sm btn-outline-primary rounded-pill']
                            )),
                          ['class' => 'text-end']
                        )
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