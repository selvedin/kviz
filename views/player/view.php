<?php

$this->title = __('Running') . '  ' . $model->title;
\yii\web\YiiAsset::register($this);
?>
<div class='card m-0 h-100'>
    <div class="card-body card-quiz h-100 d-flex align-items-center">
        <div id="stopwatch">
        </div>
        <div v-if="isPlaying && !showResults" class='w-100 h-100'>
            <div class="question-part h-50  ">
                <h1 class='quiz-question-title text-center ms-auto me-auto w-75 mt-5'>
                    {{pastQuestions.length}}/{{allQuestions.length}}<br />
                    {{question.content}}
                </h1>
            </div>
            <div class='quiz-part h-50 row'>
                <div v-if="question.question_type == 1" class="col-md-6 text-center">
                    <button @click="answerQuestion(0, '<?= __('False') ?>')" :disabled='!canAnswer' :class="[classObject, results.findIndex(r => r.q == question.id && r.a == 0) > -1 ? 'active':'']">
                        <?= __('False') ?>
                        <svg v-if="!canAnswer && question.options[0].is_true == 0" class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                            <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none" />
                            <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8" />
                        </svg>
                    </button>
                </div>
                <div v-if="question.question_type == 1" class="col-md-6 text-center">
                    <button @click="answerQuestion(1, '<?= __('True') ?>')" :disabled='!canAnswer' :class="[classObject, results.findIndex(r => r.q == question.id && r.a == 1) > -1 ? 'active':'']">
                        <?= __('True') ?>
                        <svg v-if="!canAnswer && question.options[0].is_true == 1" class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                            <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none" />
                            <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8" />
                        </svg>
                    </button>
                </div>
                <div v-else v-for="(opt, index) in question.options" class="col-md-6 text-center">
                    <button @click="answerQuestion(opt.id, opt.content)" :disabled='!canAnswer' :class="[classObject, results.findIndex(r => r.q == question.id && r.a == opt.id) > -1 ? 'active':'']">
                        {{String.fromCharCode(65+index) + ': ' + opt.content}}
                        <svg v-if="!canAnswer && opt.is_true" class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                            <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none" />
                            <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
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
            <a href='javascript:void(0)' @click='stopQuiz()' :class="classObject" style="margin-top:40px;">
                <?= __('End') ?>
            </a>
        </div>
        <h1 v-if="!isPlaying" class='text-center w-100'>
            <button type='button' :disabled="!questions.length" @click='runQuiz()' :class="classObject">
                <?= __('Run quiz') ?>: <strong><?= $model->title ?></strong>
            </button>
        </h1>
    </div>
</div>