<?php

use app\helpers\Icons;
use app\widgets\CardView;
use yii\bootstrap5\Html;

$this->title = __('View');
$summary = unserialize($model->summary);
?>
<?= CardView::begin([
  'title' => __('Quiz') . ': ' . $model->quiz->title . ', '
    . Html::tag('small', date('d.m.Y', $model->updated_at), ['class' => 'text-dark']),
  'buttons' => [],
  'type' => 'info'
]);
?>
<table class='table text-start' style="font-size:1.3rem;">
  <thead>
    <tr>
      <th>#</th>
      <th><?= __('Question') ?></th>
      <th><?= __('Options') ?></th>
      <th><?= __('Correct') ?></th>
      <th><?= __('Answer') ?></th>
      <th><?= __('Is correct') ?></th>
    </tr>
  </thead>
  <tbody>
    <?php
    $num = 1;
    $totalCorrect = 0;
    foreach ($summary as $sum) :
      $isCorrect = $sum['correct'] == $sum['answer'];
      if ($isCorrect) $totalCorrect++;
    ?>
      <tr>
        <td><?= $num++ ?>.</td>
        <td><?= $sum['title'] ?></td>
        <td><?= $sum['options'] ?></td>
        <td><?= $sum['correct'] ?></td>
        <td><?= $sum['answer'] ?></td>
        <td>
          <span><?= $isCorrect ?  Icons::Correct() : Icons::Incorrect()  ?></span>
        </td>
      </tr>
    <?php
    endforeach;
    ?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan='5'><?= __('Summary') ?></td>
      <td>
        <h3 class='text-danger'>
          <?= $totalCorrect . '/' . ($num - 1) ?>
        </h3>
      </td>
    </tr>
  </tfoot>
</table>
<?= CardView::end() ?>