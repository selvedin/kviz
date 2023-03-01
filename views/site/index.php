<?php

/** @var yii\web\View $this */

$this->title = Yii::$app->name;
$images = [
  'card-advance-sale.png',
  'boy-verify-email-dark.png',
  'boy-with-laptop-dark.png',
  'boy-with-rocket-light.png',
  'girl-unlock-password-light.png',
  'girl-doing-yoga-light.png',
  'girl-verify-password-dark.png',
  'girl-with-laptop.png',
  'girl-with-laptop-light.png',
  'page-misc-under-maintenance.png',
  'auth-forgot-password-illustration-light.png'
];
?>

<div id="siteApp">
  <?php //require_once('pages/sample.php') 
  ?>
  <?php require_once('pages/quizes.php') ?>

  <?php require_once('pages/moderate.php') ?>
</div>