<?php

use app\helpers\Icons;
use yii\bootstrap5\Html;

if ($model->options) {
?>
  <div class="col-xl-8 col-md-6 mb-4">
    <div class="card h-100">
      <div class="card-header d-flex justify-content-between">
        <div class="card-title mb-0">
          <h5 class="mb-0"><?= $model->content ?></h5>
          <small class="text-muted"></small>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive text-nowrap">
          <table class="table">
            <tbody class="table-border-bottom-0">
              <?php
              foreach ($model->options as $opt) {
                if ($opt->content == 'tf') {
                  echo Html::tag(
                    'tr',
                    Html::tag('td', __('True')) .
                      Html::tag(
                        'td',
                        ($opt->is_true
                          ? Icons::faIcon('check text-success')
                          : Icons::faIcon('times text-danger')),
                        ['class' => 'text-end']
                      )
                  );
                } else
                  echo Html::tag(
                    'tr',
                    Html::tag('td', $opt->content) .
                      Html::tag(
                        'td',
                        ($opt->is_true
                          ? Icons::faIcon('check text-success')
                          : Icons::faIcon('times text-danger')),
                        ['class' => 'text-end']
                      )
                  );
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
<?php
}
