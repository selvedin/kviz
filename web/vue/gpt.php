<?php
$action = Yii::$app->controller->action->id;
switch ($action) {
  case 'audio':
    include_once('gpt_audio.php');
    break;
  case 'ocr-list':
    include_once('gpt_ocr_list.php');
    break;
  case 'question':
    include_once('gpt_question.php');
    break;
  default:
    break;
}
