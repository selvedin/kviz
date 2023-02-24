<?php
$style = 'style="font-size:1.5rem;font-weight:bold;"';
?>
<button type='button' :disabled="!questions.length || didPlay" @click='runQuiz()' :class="[classObject, 'btn-quiz-active']">
  <strong><?= $model->title ?></strong><br />
  <small><?= __('Run quiz') ?></small>
</button>
<div class='text-start quizInfo mt-5'>
  <div class="row my-4">
    <div class="col-md-2"></div>
    <div class='col-md-4'>
      <h4 class='text-white'><?= __('Num of questions') ?>:</h4>
    </div>
    <div class='col-md-4'>
      <span class='btn btn-icon rounded-pill btn-lg btn-primary' <?= $style ?>>{{totalQuestions}}</span>
    </div>
    <div class="col-md-2"></div>
  </div>
  <div class="row my-4">
    <div class="col-md-2"></div>
    <div class='col-md-4'>
      <h4 class='text-white'><?= __('Number of questions that satisfies the quizs criteria') ?>:</h4>
    </div>
    <div class='col-md-4'>
      <span class='btn btn-icon rounded-pill btn-lg btn-warning' <?= $style ?>>{{allQuestions.length}}</span>
    </div>
    <div class="col-md-2"></div>
  </div>
  <div class="row my-4">
    <div class="col-md-2"></div>
    <div class='col-md-4'>
      <h4 class='text-white'><?= __('The Quiz duration (minutes)') ?>:</h4>
    </div>
    <div class='col-md-4'>
      <span class='btn btn-icon rounded-pill btn-lg btn-success' <?= $style ?>>{{parseInt(duration / 60)}}</span>
    </div>
    <div class="col-md-2"></div>
  </div>
  <div class="row my-4">
    <div class="col-md-2"></div>
    <div class='col-md-4'>
      <h4 class='text-white'><?= __('Time to answer the question (seconds)') ?>:</h4>
    </div>
    <div class='col-md-4'>
      <span class='btn btn-icon rounded-pill btn-lg btn-info' <?= $style ?>>{{questionTimeInSeconds}}</span>
    </div>
    <div class="col-md-2"></div>
  </div>
</div>