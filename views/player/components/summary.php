<div v-if="showSummary" class="col-md-12 text-center">
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
          <tr v-for="(item, ind) in summary.items">
            <td>{{ind+1}}.</td>
            <td>{{item.title}}</td>
            <td>
              <span>
                {{item.correct}},
              </span>
            </td>
            <td>
              <span :class="item.isCorrect?'text-success':'text-danger'">
                {{item.answer}},
              </span>
            </td>
          </tr>
        </tbody>
        <tfoot>
          <tr>
            <td colspan='3'><?= __('Summary') ?></td>
            <td>
              <h4>
                {{ summary.totalCorrect}}/{{ allQuestions.length}}
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