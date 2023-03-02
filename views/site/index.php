<?php

/** @var yii\web\View $this */

$this->title = Yii::$app->name;
$path = Yii::getAlias('@web') . '/img/illustrations/';
?>

<div id="siteApp">
  <?php //require_once('pages/sample.php') 
  ?>
  <?php require_once('pages/quizes.php') ?>

  <?php require_once('pages/moderate.php') ?>
</div>