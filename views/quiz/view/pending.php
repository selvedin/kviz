<?php

use app\helpers\Buttons;
use yii\bootstrap5\Html;

if ($perms->canView('ActiveQuiz')) :
?>
  <div class='col-12'>
    <div class="card h-100">
      <div class="card-header bg-dark d-flex justify-content-between">
        <div class="card-title mb-0">
          <h5 class="mb-0 text-white">
            <i class="fas fa-rocket"></i>
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
                    <th>#</th>
                    <th><?= __('Num of questions') ?></th>
                    <th></th>
                  </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                  <?php foreach ($model->pending as $k => $pending) {
                    echo Html::tag(
                      'tr',
                      Html::tag('td', $k + 1 . '.') .
                        Html::tag('td', $pending->quizObject->num_of_questions)
                        . Html::tag(
                          'td',
                          __isUser(Buttons::Pdf($pending->id, 'quiz-temp')) .
                            Html::a(
                              $pending->active ? __('Deactive') : __('Activate'),
                              ['quiz/activate', 'id' => $pending->id, 'active' => $pending->active ? 0 : 1],
                              ['class' => 'btn btn-sm btn-outline-primary rounded-pill']
                            )
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