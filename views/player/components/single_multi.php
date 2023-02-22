<div v-if="[2,3].includes(+question.question_type)" v-for="(opt, index) in question.options" class="col-md-6 text-center">
  <button @click="answerQuestion(opt.id, opt.content)" :disabled='!canAnswer' :class="[classObject, results.findIndex(r => r.question == question.id && r.answer == opt.id) > -1 ? 'active':'btn-quiz-active']">
    {{String.fromCharCode(65+index) + ': ' + opt.content}}
    <svg v-if="!canAnswer && opt.is_true" class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
      <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none" />
      <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8" />
    </svg>
  </button>
</div>