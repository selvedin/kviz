<?php

use app\models\ApiCalls;
use app\models\Question;
use app\widgets\AccordionCard;
use yii\bootstrap5\Html;
use yii\helpers\Url;

?>

<div class="col-xl-6 col-md-6 mb-4">
  <div class="card h-100">
    <div class="card-header d-flex justify-content-between">
      <div class="card-title mb-0">
        <h5 class="mb-0"><?= __('Previously generated') ?></h5>
      </div>
    </div>
    <div class="card-body">
      <?php
      foreach ($files as $file) {
        $fileContent = ApiCalls::processFile($file);
        echo AccordionCard::begin([
          'id' => $file,
          'title' => date("d.m.Y", $file) . ' - ' . $fileContent[0], 'show' => false
        ]);

        for ($i = 1; $i < count($fileContent); $i++) {
          $parts = explode("\n", $fileContent[$i]);
          foreach ($parts as $k => $part) {
            if (empty($part)) continue;
            if ($k == 0) {
              $existing = Question::find()->where(['content' => $part])->exists();
              echo Html::tag(
                'div',
                Html::tag('strong', $part)
                  . (!$existing ? Html::a(
                    __('Save a question'),
                    Url::to([
                      'question/add-generated',
                      'question' => serialize($parts),
                      'details' => $fileContent[0]
                    ]),
                    ['class' => 'btn btn-outline-primary btn-sm rounded-pill']
                  ) : ''),
                ['class' => 'd-flex justify-content-between']
              );
            } else {
              $part = str_replace(['a) ', 'b) ', 'c) ', 'd) ', '[]', '[ ]'], '', $part);
              $part = str_replace('[x]', $check, $part);
              echo Html::tag('div', $part);
            }
          }
          echo Html::tag('hr');
        }
        echo AccordionCard::end();
      }
      ?>
    </div>
  </div>
</div>