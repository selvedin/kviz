<?php

use yii\bootstrap5\Html;

if ($model->config) :
?>
  <div class='col-xl-8 col-md-6 mb-4'>
    <div class="card h-100">
      <div class="card-header d-flex justify-content-between">
        <div class="card-title mb-0">
          <h5 class="mb-0"><?= __('Config') ?></h5>
          <small class="text-muted"></small>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive text-nowrap">
          <table class="table">
            <thead>
              <tr>
                <th>#</th>
                <th><?= __('Num Of Questions') ?></th>
                <th><?= __('Question type') ?></th>
                <th><?= __('Grade') ?></th>
                <th><?= __('Level') ?></th>
                <th><?= __('Category') ?></th>
              </tr>
            </thead>
            <tbody class="table-border-bottom-0">
              <?php
              foreach ($model->config as $k => $conf)
                echo Html::tag(
                  'tr',
                  Html::tag('td', $k + 1 . '.') .
                    Html::tag('td', $conf->num_of_questions)
                    . Html::tag('td', $conf->questionType)
                    . Html::tag('td', $conf->gradeLabel?->title)
                    . Html::tag('td', $conf->levelLabel)
                    . Html::tag('td', $conf->category?->name)
                );
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
<?php
endif;
?>