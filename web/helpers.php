<?php

use yii\bootstrap5\Html;

function __label($title)
{
  return Html::label(__($title) . ':', str_replace(' ', '_', $title), ['class' => 'form-label']);
}

function __modalTemplate($modalName)
{
  $t = "<div class='form-group'>{label}<div class='d-flex justify-content-between'>{input}";
  $t .= "<button type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='$modalName'><i class='fa fa-plus'></i></button>";
  $t .= "</div></div>";
  return $t;
}
