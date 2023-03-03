<?php

use app\helpers\Icons;
use app\widgets\CardView;
use yii\bootstrap5\Html;

$this->title = __('Results');
echo  CardView::begin([
  'title' => $this->title,
  'type' => 'info',
  'buttons' => [],
]);
$summary = unserialize($results->summary);
?>
<div class="row">
  <div class="col-12">
    <table class="table">
      <thead>
        <tr>
          <th>#</th>
          <th><?= __('Question') ?></th>
          <th><?= __('Options') ?></th>
          <th><?= __('Right') ?></th>
          <th><?= __('Answer') ?></th>
          <th><?= __('Is correct') ?></th>
        </tr>
      </thead>
      <tbody>
        <?php
        foreach ($summary as $k => $item) {
          echo Html::tag(
            'tr',
            Html::tag('td', ++$k . '.') .
              Html::tag('td', $item['title']) .
              Html::tag('td', $item['options']) .
              Html::tag('td', $item['correct']) .
              Html::tag('td', $item['answer']) .
              Html::tag('td', $item['isCorrect'] ? Icons::Correct() : Icons::Incorrect())
          );
        }
        ?>
      </tbody>
    </table>
  </div>
</div>
<?= CardView::end() ?>