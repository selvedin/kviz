<div class="col-xl-6 col-md-6 mb-4">
  <div class="card h-100">
    <div class="card-header d-flex justify-content-between">
      <div class="card-title mb-0">
        <h5 class="mb-0"><?= __('Results') ?></h5>
      </div>
    </div>
    <div class="card-body">
      <?php

      use yii\bootstrap5\Html;

      if (is_array($response))
        foreach ($response as $question) {
          $parts = explode("\n", $question);
          foreach ($parts as $k => $part) {
            if ($k == 0)
              echo Html::tag('div', Html::tag('strong', $part));
            else {
              $part = str_replace('[x]', $check, $part);
              $part = str_replace('[', '', $part);
              $part = str_replace(']', '', $part);
              echo Html::tag('div', $part);
            }
          }
          echo Html::tag('hr');
        }
      ?>
    </div>
  </div>
</div>