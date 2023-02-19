<?php

use yii\bootstrap5\Html;

$this->title = __('Running') . '  ' . $model->title;
\yii\web\YiiAsset::register($this);
?>
<div class='card m-0 h-100'>
    <div class="card-body card-quiz h-100 d-flex align-items-center">
        <div v-if="isPlaying && !showResults" class='text-center w-100 h-100'>
            <div class="question-part h-50  ">
                <h1 class='quiz-question-title ms-auto me-auto w-75 mt-5'>{{question.content}}</h1>
            </div>
            <div class='quiz-part h-50 row'>
                <div v-if="question.question_type == 1" class="col-md-6 text-center">
                    <a href='javascript:void(0)' @click="answerQuestion(0)" class='btn rounded-pill btn-outline-info btn-quiz w-75'>
                        <?= __('False') ?>
                    </a>
                </div>
                <div v-if="question.question_type == 1" class="col-md-6 text-center">
                    <a href='javascript:void(0)' @click="answerQuestion(1)" class='btn rounded-pill btn-outline-info btn-quiz w-75'>
                        <?= __('True') ?>
                    </a>
                </div>
                <div v-else v-for="(opt, index) in question.options" class="col-md-6 text-center">
                    <a href='javascript:void(0)' @click="answerQuestion(opt.id)" class='btn rounded-pill btn-outline-info btn-quiz w-75'>
                        {{String.fromCharCode(65+index) + ': ' + opt.content}}
                    </a>
                </div>
            </div>
        </div>
        <div v-if="showResults" class='text-center w-100 h-100'>
            <h4 v-for="result in results">{{result.q + " - " + result.a}}</h4>
        </div>
        <h1 v-if="!isPlaying" class='text-center w-100'>
            <a href='javascript:void(0)' class='btn rounded-pill btn-outline-primary waves-effect btn-xl w-25' @click='runQuiz()'>
                <?= __('Run quiz') ?>: <strong><?= $model->title ?></strong>
            </a>
        </h1>
    </div>
</div>