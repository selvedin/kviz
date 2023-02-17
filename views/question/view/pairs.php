<?php

use app\widgets\CardView;
use yii\bootstrap5\Html;

if ($model->pairs) {
  echo  CardView::begin([
    'title' => __('Pairs'),
    'type' => 'warning',
    'buttons' => [],
  ]);
?>
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
<?php
  echo CardView::end();
}
