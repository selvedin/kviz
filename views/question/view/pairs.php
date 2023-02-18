<?php

use yii\bootstrap5\Html;

if ($model->pairs) {
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
              foreach ($model->pairs as $opt)
                echo Html::tag(
                  'tr',
                  Html::tag('td', $opt->one) . Html::tag('td', $opt->two)
                );
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
<?php
}
