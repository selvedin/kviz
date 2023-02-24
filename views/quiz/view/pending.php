<?php

use app\helpers\Buttons;
use app\helpers\Icons;
use yii\bootstrap5\Html;

$badge = Html::tag(
  'span',
  Html::tag('i', '', ['class' => 'fas fa-times']),
  [
    'class' => 'badge badge-center rounded-pill bg-danger ms-4',
    'style' => 'margin-right:-15px;'
  ]
);
if ($perms->canView('ActiveQuiz')) :
?>
  <div class='col-12'>
    <div class="card h-100">
      <div class="card-header bg-dark d-flex justify-content-between">
        <div class="card-title mb-0">
          <h5 class="mb-0 text-white">
            <i class="fas fa-stopwatch"></i>
            <?= __('Pending Quizes') ?>
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
                  <?php foreach ($model->pending as $k => $pending) {
                    $competitors = "";
                    foreach ($pending->competitors as $c) {
                      $competitors .= Html::a(
                        Html::tag('span', $c->user->name, ['class' => 'ms-auto']) . $badge,
                        'javascript:void(0)',
                        [
                          'class' => 'rounded-pill btn btn-outline-secondary me-2',
                          '@click' => "removeCompetitor($c->id)"
                        ]
                      );
                    }
                    echo "<tr>";
                    echo Html::tag('td', $k + 1 . '.');
                    echo Html::tag('td', $pending->quizObject->num_of_questions);
                    echo Html::tag(
                      'td',
                      $competitors .
                        Html::a(
                          Icons::faIcon('plus'),
                          '#',
                          [
                            'class' => 'btn btn-icon btn-success rounded-pill ms-2',
                            // 'data-bs-toggle' => 'modal',
                            // 'data-bs-target' => '#competitorsModal',
                            '@click' => "toggleModal($pending->id)",
                            'title' => __('Add competitor')
                          ]
                        )
                    );
                    echo Html::tag(
                      'td',
                      __isUser(Buttons::Pdf($pending->id, 'quiz-temp')) .
                        (count($pending->competitors) ? Html::a(
                          __('Activate'),
                          ['quiz/activate', 'id' => $pending->id, 'active' => 1],
                          ['class' => 'btn btn-sm btn-outline-primary rounded-pill']
                        ) : null)
                        . Html::a(
                          __('Delete'),
                          ['quiz-temp/delete', 'id' => $pending->id],
                          [
                            'class' => 'btn btn-sm btn-outline-danger rounded-pill ms-2',
                            'data-method' => 'POST',
                            'data-confirm' => __('Are You sure')
                          ]
                        ),
                      ['class' => 'text-end']
                    );
                    echo "</tr>";
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