<?php
$style = 'style="font-size:1.5rem;font-weight:bold;"';
?>
<button v-if="!isRemote" type='button' :disabled="!questions.length || didPlay" @click='runQuiz()' :class="[classObject, 'btn-quiz-active']">
  <strong><?= $model->title ?></strong><br />
  <small><?= __('Run quiz') ?></small>
</button>
<button v-else @click='runQuiz()' class='ms-auto me-auto rounded-pill btn-quiz btn-quiz-active animate__animated animate__pulse animate__infinite'>
  <span v-if="isModerator"><?= __('Moderate the quiz') ?> ...</span>
  <span v-else><?= __('Waiting a Moderator to start the quiz') ?> ...</span>
</button>
<div class='text-start quizInfo mt-5'>
  <div class=" row my-4">
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
  <div class="info-tag" v-if="isRunning && (!isRemote || isModerator)">
    <span><?= __('Press SPACE to start the Quiz') ?></span>
  </div>
</div>