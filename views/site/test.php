<?php

use app\helpers\Icons;
use app\models\Options;
use app\models\Pairs;
use app\models\Question;
use app\models\QuizTemp;
use app\models\User;
use yii\bootstrap5\Html;

$this->title = 'Test';

$results = QuizTemp::findOne(5);
$temp = unserialize($results->results);
?>
<div class="card">
  <div class='card-body'>
    <?php
    foreach ($temp as $k => $t) {
      echo "<table class='table'><thead><tr><th colspan='4'>";
      echo Html::tag('h5', User::findOne($k)->name);
      echo "</th></tr>";
      echo "<tr><th></th><th>Pitanje</th><th>Opcije</th><th>Tacno</th><th>Odgovor</th><th>Rezultat</th></tr>";
      echo "</thead><tbody>";
      $res = unserialize($t);
      $res = $res['results'];
      $processed = [];
      $num = 1;
      $totalCorrect = 0;
      foreach ($res as $ka => $va) {
        $question = Question::findOne($va['question']);
        if (in_array($question->id, $processed)) continue;
        if ($question->question_type == 3) {
          $processed[] = $question->id;
          $answer = getOptions($question->id, $res);
        } else if ($question->question_type == 4) {
          $processed[] = $question->id;
          $answer = getPairs($question->id, $res);
        } else
          $answer = resolveAnswer($question, $va);
        $options = resolveOptions($question);
        $correct = resolveCorrect($question);
        $isCorrect = $answer == $correct;
        if ($isCorrect) $totalCorrect++;
        echo "<tr>";
        echo "<td>$num.</td>";
        echo "<td>$question->content</td>";
        echo "<td>$options</td>";
        echo "<td>$correct</td>";
        echo "<td>$answer</td>";
        echo "<td>" . ($isCorrect ? Icons::Correct() : Icons::Incorrect()) . "</td>";
        echo "</tr>";
        $num++;
      }
      echo "</tbody>";
      echo "<tfoot><tr><td colspan='5'></td><td>";
      echo Html::tag('h4', $totalCorrect . '/' . --$num . " [ " . round($totalCorrect / $num * 100) . "% ]");
      echo "</td></tr></tfoot>";
      echo "</table>";
    }
    ?>
  </div>
</div>
<?php
function resolveOptions(Question $question)
{
  $qt = $question->question_type;
  if ($qt == 1) return "TRUE/FALSE";
  if (in_array($qt, [2, 3, 5])) return $question->OptionsAsString();
  if ($qt == 4) return $question->PairsAsString();
}

function resolveCorrect(Question $question)
{
  $qt = $question->question_type;
  if ($qt == 1) return $question->isTrue ? 'TRUE' : 'FALSE';
  if (in_array($qt, [2, 3, 5])) return $question->CorrectOptionsAsString();
  if ($qt == 4) return $question->PairsAsString();
}

function resolveAnswer(Question $question, $data)
{
  $qt = $question->question_type;
  if ($qt == 1) return $data['answer'] ? 'TRUE' : 'FALSE';
  if ($qt == 2) return Options::findOne($data['answer'])->content;
  if ($qt == 5) return $data['answer'];
  return null;
}

function getOptions($id, $res)
{
  $data = [];
  foreach ($res as $d)
    if ($d['question'] == $id)
      $data[] = Options::findOne($d['answer'])->content;
  sort($data);
  return implode(', ', $data);
}

function getPairs($id, $res)
{
  $data = [];
  foreach ($res as $d)
    if ($d['question'] == $id) {
      $data[] = $d['leftContent'] . " - " . $d['rightContent'];
    }
  sort($data);
  return implode("<br/>", $data);
}
