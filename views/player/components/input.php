<div class="col-md-3"></div>
<div v-if="question.question_type == 5" class="col-md-6 text-center">
  <input type='text' @keyup="answerQuestion($event)" :disabled='!canAnswer' class="form-control text-center question-input" placeholder="<?= __('Result is ...') ?>">
  </input>
</div>
<div class="col-md-3"></div>