<?php

use app\helpers\Icons;
use app\models\Question;
use app\widgets\CardView;
use yii\bootstrap5\Html;

$this->title = __('Details');
$quiz = unserialize($model->quiz);
$options = $corrects = $summaries = $totals = [];
$totalQuestion = count($quiz);

foreach ($quiz as $q) {
  $question = Question::findOne((int)$q['id']);
  $options[] = $question->OptionsAsString();
  $corrects[] = $question->CorrectOptionsAsString();
}
?>
<?= CardView::begin([
  'title' => __('Quiz') . ': ' . $model->quizObject->title . ', '
    . Html::tag('small', date('d.m.Y', $model->updated_at), ['class' => 'text-dark']),
  'buttons' => [],
  'type' => 'info'
]);
?>
<table class='table'>
  <thead>
    <tr>
      <th>#</th>
      <th><?= __('Question') ?></th>
      <th><?= __('Options') ?></th>
      <th><?= __('Correct') ?></th>
      <?php
      foreach ($model->userResults as $res) {
        echo Html::tag('th', $res->user->name);
        $summary = unserialize($res->summary);
        $total = unserialize($res->totals);
        $totals[$res->competitor_id] = $total['totalCorrect'];
        foreach ($summary as $sum) $summaries[$res->competitor_id][] = $sum['isCorrect'];
      }
      ?>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>
        <?php for ($i = 1; $i <= $totalQuestion; $i++) echo Html::tag('div', "$i ."); ?>
      </td>
      <td>
        <?php
        foreach ($quiz as $q) echo Html::tag('div', $q['content']);
        ?>
      </td>
      <td>
        <?php
        foreach ($options as $o) echo Html::tag('div', $o);
        ?>
      </td>
      <td>
        <?php
        foreach ($corrects as $c) echo Html::tag('div', $c);
        ?>
      </td>
      <?php
      foreach ($summaries as $summary) {
        echo "<td>";
        foreach ($summary as $isCorrect)
          echo Html::tag('div', $isCorrect ? Icons::Correct('') : Icons::Incorrect(''), ['class' => 'text-center']);
        echo "</td>";
      }
      ?>
    </tr>
  </tbody>
  <tfoot>
    <tr>
      <td colspan='4'>
        <strong>
          <?= __('Summary') ?>
        </strong>
      </td>
      <?php
      foreach ($totals as $t)
        echo Html::tag('td', Html::tag('h5', $t . '/' . $totalQuestion, ['class' => 'text-danger']), ['class' => 'text-center']);
      ?>
      </td>
    </tr>
  </tfoot>
</table>
<?= CardView::end() ?>
<?php
function getQuestionDetails($res)
{
}
?>