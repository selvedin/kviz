<?php


use app\models\User;
use kartik\select2\Select2;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

?>

<div class="modal fade" id="competitorsModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><?= __('Competitor')  ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <?php ActiveForm::begin(['id' => 'competitors-form']); ?>
        <?= Html::hiddenInput('QuizCompetitors[quiz_id]', $model->id) ?>
        <?= Html::hiddenInput('QuizCompetitors[temp_id]', null) ?>

        <?= Select2::widget([
          'name' => 'QuizCompetitors[competitor_id]',
          'data' => User::list(),
          'options' =>  ['id' => 'quiz-competitor', 'placeholder' => __('Select a chapter') . '...',],
          'pluginOptions' => [
            'tags' => true,
            'dropdownParent' => '#competitorsModal'
          ]
        ]) ?>
        <?php ActiveForm::end(); ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary waves-effect waves-light" data-bs-dismiss="modal" @click="addCompetitor()">
          <?= __('Save') ?>
        </button>
      </div>
    </div>
  </div>
</div>