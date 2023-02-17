<?php

use app\helpers\Icons;
use app\widgets\CardView;
use yii\bootstrap5\Html;

if ($model->options) {
  echo  CardView::begin([
    'title' => __('Options'),
    'type' => 'warning',
    'buttons' => [],
  ]);
?>
  <div class="table-responsive text-nowrap">
    <table class="table">
      <tbody class="table-border-bottom-0">
        <?php
        foreach ($model->options as $opt)
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
        ?>
      </tbody>
    </table>
  </div>
<?php
  echo CardView::end();
}
