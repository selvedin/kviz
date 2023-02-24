<?php

use app\helpers\Icons;
use app\models\User;
use kartik\select2\Select2;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

?>

<div class="modal fade" id="summaryModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable" role="document" style="--bs-modal-width: 90vw;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><?= __('Summary')  ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <table class='table text-start' style="font-size:1.3rem;">
          <thead>
            <tr>
              <th>#</th>
              <th><?= __('Question') ?></th>
              <th><?= __('Options') ?></th>
              <th><?= __('Correct') ?></th>
              <th><?= __('Answer') ?></th>
              <th><?= __('Is correct') ?></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, ind) in userSummary">
              <td>{{ind+1}}.</td>
              <td>{{item.title}}</td>
              <td>{{item.options}}</td>
              <td>{{item.correct}}</td>
              <td>{{item.answer}}</td>
              <td>
                <span v-if="item.isCorrect"><?= Icons::Correct() ?></span>
                <span v-else><?= Icons::Incorrect() ?></span>
              </td>
            </tr>
          </tbody>
          <tfoot>
            <tr>
              <td colspan='5'><?= __('Summary') ?></td>
              <td>
                <h3 class='text-danger'>
                  {{ userSummary.filter(item=>item.isCorrect).length}}/{{ userSummary.length}}
                </h3>
              </td>
            </tr>
          </tfoot>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary waves-effect waves-light" data-bs-dismiss="modal" @click="userSummary=[]">
          <?= __('Close') ?>
        </button>
      </div>
    </div>
  </div>
</div>