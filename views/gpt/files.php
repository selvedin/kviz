<?php

use app\helpers\GptHelper;
use app\helpers\Icons;
use app\models\ApiCalls;
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
        $filename = str_replace(".txt", "", $file);
        $title = date("d.m.Y", $filename) . ' - ' . $fileContent[0];
        $title .= Html::a(
          Icons::faIcon('trash'),
          Url::to(['gpt/delete-file', 'file' => $file]),
          [
            'class' => 'btn-link btn-sm text-danger',
            'data' => [
              'confirm' => __('Are you sure you want to delete this file?'),
              'method' => 'post',
            ]
          ]
        );
        echo AccordionCard::begin([
          'id' => $filename,
          'title' => Html::tag('div', $title, ['class' => 'd-flex justify-content-between w-100']),
          'show' => false
        ]);
        echo Html::tag('h5', __('Questions'));
        echo Html::tag('hr');
        echo GptHelper::resolveQuestions($fileContent);
        echo AccordionCard::end();
      }
      ?>
    </div>
  </div>
</div>