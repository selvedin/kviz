<button type='button' :disabled="!questions.length" @click='runQuiz()' :class="[classObject, 'btn-quiz-active']">
  <?= __('Run quiz') ?>: <strong><?= $model->title ?></strong>
</button>
<div class='text-start quizInfo'>
  <div class="row">
    <div class="col-md-2"></div>
    <div class='col-md-4'>
      <h4 class='text-white'><?= __('Num of questions') ?>:</h4>
    </div>
    <div class='col-md-4'>
      <span class='badge badge-center rounded-pill bg-primary'>{{totalQuestions}}</span>
    </div>
    <div class="col-md-2"></div>
  </div>
  <div class="row">
    <div class="col-md-2"></div>
    <div class='col-md-4'>
      <h4 class='text-white'><?= __('Number of questions that satisfies the quizs criteria') ?>:</h4>
    </div>
    <div class='col-md-4'>
      <span class='badge badge-center rounded-pill bg-warning'>{{allQuestions.length}}</span>
    </div>
    <div class="col-md-2"></div>
  </div>
  <div class="row">
    <div class="col-md-2"></div>
    <div class='col-md-4'>
      <h4 class='text-white'><?= __('The Quiz duration (minutes)') ?>:</h4>
    </div>
    <div class='col-md-4'>
      <span class='badge badge-center rounded-pill bg-success'>{{parseInt(duration / 60)}}</span>
    </div>
    <div class="col-md-2"></div>
  </div>
  <div class="row">
    <div class="col-md-2"></div>
    <div class='col-md-4'>
      <h4 class='text-white'><?= __('Time to answer the question (seconds)') ?>:</h4>
    </div>
    <div class='col-md-4'>
      <span class='badge badge-center rounded-pill bg-info'>{{questionTimeInSeconds}}</span>
    </div>
    <div class="col-md-2"></div>
  </div>
</div>