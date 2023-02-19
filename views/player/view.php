<?php

use yii\bootstrap5\Html;

$this->title = __('Running') . '  ' . $model->title;
\yii\web\YiiAsset::register($this);
?>
<div class='card m-0 h-100'>
    <div class="card-body card-quiz h-100 d-flex align-items-center">
        <div id="stopwatch">
        </div>
        <div v-if="isPlaying && !showResults" class='w-100 h-100'>
            <div class="question-part h-50  ">
                <h1 class='quiz-question-title ms-auto me-auto w-75 mt-5'>
                    {{question.content}}
                </h1>
            </div>
            <div class='quiz-part h-50 row'>
                <div v-if="question.question_type == 1" class="col-md-6 text-center">
                    <button @click="answerQuestion(0)" :disabled='!canAnswer' :class="[classObject, results.findIndex(r => r.q == question.id && r.a == 0) > -1 ? 'active':'']">
                        <?= __('False') ?>
                        <svg v-if="!canAnswer && opt.is_true" class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                            <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none" />
                            <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8" />
                        </svg>
                    </button>
                </div>
                <div v-if="question.question_type == 1" class="col-md-6 text-center">
                    <button @click="answerQuestion(1)" :disabled='!canAnswer' :class="[classObject, results.findIndex(r => r.q == question.id && r.a == 1) > -1 ? 'active':'']">
                        <?= __('True') ?>
                        <svg v-if="!canAnswer && opt.is_true" class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                            <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none" />
                            <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8" />
                        </svg>
                    </button>
                </div>
                <div v-else v-for="(opt, index) in question.options" class="col-md-6 text-center">
                    <button @click="answerQuestion(opt.id)" :disabled='!canAnswer' :class="[classObject, results.findIndex(r => r.q == question.id && r.a == opt.id) > -1 ? 'active':'']">
                        {{String.fromCharCode(65+index) + ': ' + opt.content}}
                        <svg v-if="!canAnswer && opt.is_true" class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                            <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none" />
                            <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <div v-if="showResults && isPlaying" class='text-center w-100 h-100'>
            <h4 v-for="result in results">{{result.q + " - " + result.a}}</h4>
            <a href='javascript:void(0)' @click='stopQuiz()' :class="classObject">
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