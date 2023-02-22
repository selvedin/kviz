<div v-if="showResults && !questions.length" class='text-center w-100 h-100'>
  <div class="card">
    <div class="card-body">
      <table class='table text-start' style="font-size:1.3rem;">
        <thead>
          <tr>
            <th>#</th>
            <th><?= __('Question') ?></th>
            <th><?= __('Correct answer') ?></th>
            <th><?= __('Your answer') ?></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(q, ind) in summary">
            <td>{{ind+1}}.</td>
            <td>{{q.label}}</td>
            <td>
              <span>
                {{q.correct}},
              </span>
            </td>
            <td>
              <span :class="q.isCorrect?'text-success':'text-danger'">
                {{q.answer}},
              </span>
            </td>
          </tr>
        </tbody>
        <tfoot>
          <tr>
            <td colspan='3'><?= __('Summary') ?></td>
            <td>
              <h4>
                {{ totalCorrect}}/{{ allQuestions.length}}
                [{{ totalPercentage }}%]
              </h4>
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
  <a href='javascript:void(0)' @click='stopQuiz()' :class="[classObject, 'btn-quiz-active']" style="margin-top:40px;">
    <?= __('End') ?>
  </a>
</div>