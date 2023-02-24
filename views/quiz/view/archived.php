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
                    <th style="width:20%;"></th>
                  </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                  <?php foreach ($model->archived as $k => $archived) {
                    $competitors = "";
                    foreach ($archived->competitors as $c) {
                      $competitors .= Html::a(
                        Html::tag('span', $c->user->name, ['class' => 'ms-auto']),
                        'javascript:void(0)',
                        [
                          'class' => 'rounded-pill btn btn-outline-secondary me-2',
                        ]
                      );
                    }
                    echo Html::tag(
                      'tr',
                      Html::tag('td', $k + 1 . '.') .
                        Html::tag('td', $archived->quizObject->num_of_questions)
                        . Html::tag('td', $competitors)
                        . Html::tag(
                          'td',
                          Html::a(
                            __('Summary'),
                            ['quiz-temp/summary', 'id' => $archived->id],
                            ['class' => 'btn btn-sm btn-outline-info rounded-pill']
                          ),
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