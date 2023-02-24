<?php

use app\helpers\Icons;
use app\models\Options;
use app\models\Pairs;
use app\models\Question;
use app\models\QuizTemp;
use app\models\User;
use yii\bootstrap5\Html;

$this->title = 'Test';

$temp = QuizTemp::findOne(7);
$results = $temp->processResults(4);
?>
<div class="card">
  <div class='card-body'>
    <table class="table">
      <thead>
        <tr>
          <th>Question</th>
          <th>Items</th>
          <th>Correct</th>
          <th>Answer</th>
        </tr>
      </thead>
      <tbody>

        <?php
        foreach ($results['items'] as $item) {
          echo Html::tag(
            'tr',
            Html::tag('td', $item['title']) .
              Html::tag('td', $item['options']) .
              Html::tag('td', $item['correct']) .
              Html::tag('td', $item['answer'])
          );
        }
        ?>
      </tbody>
    </table>
  </div>
</div>