<?php
$style = 'style="font-size:1.5rem;font-weight:bold;"';
?>
<button v-if="showRunButton" type='button' :disabled="disableRunButton" @click='runQuiz()' :class="[classObject, 'btn-quiz-active']">
  <strong><?= $model->title ?></strong><br />
  <small><?= __('Run quiz') ?></small>
</button>
<button v-if="!showRunButton && !showResults" @click='runQuiz()' class='ms-auto me-auto rounded-pill btn-quiz btn-quiz-active animate__animated animate__pulse animate__infinite'>
  <span v-if="isModerator"><?= __('Moderate the quiz') ?> ...</span>
  <span v-else><?= __('Waiting a Moderator to start the quiz') ?> ...</span>
</button>
<?php require_once('details.php') ?>