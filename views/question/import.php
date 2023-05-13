<?php

use app\helpers\Buttons;
use app\helpers\Helper;
use app\widgets\CardView;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = __('Quiz');
$buttons = [Buttons::Save(), Buttons::List()];
?>

<div class="row" id='quizExcelApp'>
  <?php ActiveForm::begin(['id' => 'create-from-excel-form', 'options' => ['enctype' => 'multipart/form-data']]); ?>
  <div class="col-12">
    <div class="card" style="background:none; border:none;box-shadow:none;">
      <div id="sticky-wrapper" class="sticky-wrapper" style="height: 80px;">
        <div class="card-header sticky-element bg-label-info d-flex justify-content-between">
          <h1 class="me-auto"><?= $this->title ?></h1>
          <div class="ms-auto">
            <ul class='card-inlinelist list-inline mb-0'>
              <li class='list-inline-item'>
                <?php foreach ($buttons as $button) echo $button; ?>
              </li>
            </ul>
            <?= Helper::dropDown($buttons) ?>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-body">
              <div class="row">
                <div class="col-12">
                  <?php require_once('parts/excel_form.php') ?>
                </div>
                <div class="col-12 border-top mt-2 pt-2">
                  <h5 class='title'><?= __('List of questions from file') ?></h5>
                  <table class='table table-striped'>
                    <thead>
                      <tr>
                        <th>#</th>
                        <th><?= __('Question') ?></th>
                        <th><?= __('Correct') ?></th>
                        <?php for ($i = 1; $i < $model['numberOfOptions']; $i++) echo Html::tag('th', __('Option') . ' ' . $i) ?>
                      </tr>
                    </thead>
                    <tbody>

                      <?php
                      if ($data) {
                        $errors = [];
                        $sheet = $data->getActiveSheet();
                        $rows = $sheet->toArray();
                        foreach ($rows as $k => $row) {
                          $error = false;
                          if (trim($row[0]) == '' || trim($row[1]) == '') $error = true;
                          $answers = Html::tag('td', $row[1]);
                          for ($i = 1; $i < $model['numberOfOptions']; $i++) {
                            if (trim($row[$i]) == '') $error = true;
                            $answers .= Html::tag('td', $row[$i]);
                          }

                          if ($k && !empty($row[0])) {
                            if ($error) $errors[] = $k;
                            echo Html::tag(
                              'tr',
                              Html::tag('td', $k) .  Html::tag('td', $row[0]) . $answers,
                              ['class' => $error ? 'text-bold text-danger' : '']
                            );
                          }
                        }
                      }
                      ?>
                    </tbody>
                    <tfoot <?= $data ? '' : 'hidden' ?>>
                      <tr>
                        <td colspan='<?= $model['numberOfOptions'] + 2 ?>' class='text-end'>
                          <h4>
                            <?php if (isset($errors) && $errors) echo Html::tag('div', __('Errors in rows') . ': ' . implode(', ', $errors), ['class' => 'text-danger']) ?>
                            <small><?= __('These questions will not be imported') ?></small>
                          </h4>
                        </td>
                      </tr>
                      <tr>
                        <td colspan='<?= $model['numberOfOptions'] + 2 ?>' class='text-end'>
                          <?= Html::submitButton(__('Import Questions'), [
                            'class' => 'btn btn-primary',
                            'onclick' => "$('#createQuestion').val(1);$('#create-from-excel-form').submit()"
                          ]) ?>
                        </td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
                <div hidden class="col-12">
                  <div class="row border-top mt-4">
                    <div class="col-12">
                      <h3><?= __('Uploaded files') ?></h3>
                    </div>
                    <div class="col-12">
                      <?php
                      $folderPath =  Yii::$app->basePath . "/web/" . EXCEL_FILES_PATH . '/' . Yii::$app->user->id . "/";
                      if (file_exists($folderPath)) {
                        $files = array_diff(scandir($folderPath), array('.', '..'));
                        foreach ($files as $file) {
                          echo  Html::tag(
                            'h6',
                            Html::a($file, ['quiz/create-from-excel', 'file' => $file])
                          );
                        }
                      }
                      ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php ActiveForm::end(); ?>
</div>