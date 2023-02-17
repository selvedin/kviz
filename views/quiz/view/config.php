<?php

use app\widgets\CardView;
use yii\bootstrap5\Html;

if ($model->config) {
  echo  CardView::begin([
    'title' => __('Config'),
    'type' => 'warning',
    'buttons' => [],
  ]);
?>
  <div class="table-responsive text-nowrap">
    <table class="table">
      <thead>
        <tr>
          <th>#</th>
          <th><?= __('Num Of Questions') ?></th>
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
              . Html::tag('td', $conf->gradeLabel)
              . Html::tag('td', $conf->levelLabel)
              . Html::tag('td', $conf->category)
          );
        ?>
      </tbody>
    </table>
  </div>
<?php
  echo CardView::end();
}
