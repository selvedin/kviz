<?php

use app\models\GptQuestion;

$this->title = 'Test';
$content = "SUBJECT: Moja okolina, GRADE: 3, UNIT_TITLE: Godišnja doba, NUM_OF_QUESTIONS: 5, LESSON_TITLE: , QUESTION_TYPE: 2";
$all = [];
$test = explode(',', $content);
foreach ($test as $part) {
  $item = explode(':', $part);
  $all[] = $item;
}
$model = GptQuestion::resolveQuestion($content);
?>
<div class="card">
  <div class='card-body'>
    <div>SUBJECT: <?= $model->subject  ?></div>
    <div>GRADE: <?= $model->grade  ?></div>
    <div>UNIT_TITLE: <?= $model->title  ?></div>
    <div>NUM_OF_QUESTIONS: <?= $model->num_of_questions  ?></div>
    <div>LESSON_TITLE: <?= $model->lesson  ?></div>
    <div>QUESTION_TYPE: <?= $model->questionType  ?></div>
  </div>
  <pre>
    <?php print_r($all) ?>
  </pre>
</div>