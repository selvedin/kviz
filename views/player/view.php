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
                <?php require_once('components/true_false.php') ?>
                <?php require_once('components/single_multi.php') ?>
                <?php require_once('components/join.php') ?>
                <?php require_once('components/input.php') ?>
            </div>
        </div>

        <?php require_once('components/summary.php') ?>

        <h1 v-if="!isPlaying" class='text-center w-100'>
            <?php require_once('components/info.php') ?>
        </h1>
    </div>
</div>