<div v-if="question.question_type == 1" class="col-md-6 text-center">
  <button @click="answerQuestion(0, '<?= __('False') ?>')" :disabled='!canAnswer' :class="[classObject, results.findIndex(r => r.question == question.id && r.answer == 0) > -1 ? 'active':'btn-quiz-active']">
    <?= __('False') ?>
    <svg v-if="!canAnswer && question.options[0].is_true == 0" class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
      <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none" />
      <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8" />
    </svg>
  </button>
</div>
<div v-if="question.question_type == 1" class="col-md-6 text-center">
  <button @click="answerQuestion(1, '<?= __('True') ?>')" :disabled='!canAnswer' :class="[classObject, results.findIndex(r => r.question == question.id && r.answer == 1) > -1 ? 'active':'btn-quiz-active']">
    <?= __('True') ?>
    <svg v-if="!canAnswer && question.options[0].is_true == 1" class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
      <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none" />
      <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8" />
    </svg>
  </button>
</div>